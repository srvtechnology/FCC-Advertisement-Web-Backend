<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateBulkPdf;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class BookingPdfController extends Controller
{
    public function downloadPDF($id)
    {
        $booking = Booking::with(['user', 'space','payment','payments'])->withSum('payments', 'payment_amount_1')->findOrFail($id);
        // Attach the payment amount field
        $booking->payment_amount = Payment::where('booking_id', $id)->value('amount') ?? 'N/A';

        $pdf = Pdf::loadView('demand_note', compact('booking'));
        return $pdf->download("Demand_Note_{$booking->id}.pdf");
    }


    



public function downloadBulkPDF(Request $request)
{
    $ids = $request->input('ids');

    if (!is_array($ids) || empty($ids)) {
        return response()->json(['error' => 'Invalid request'], 400);
    }

    // Fetch booking data
    $bookings = Booking::with(['user', 'space','payment','payments'])->withSum('payments', 'payment_amount_1')->whereIn('id', $ids)->get();

    // Attach payment amount
    $bookings = $bookings->map(function ($b) {
        $b->payment_amount = Payment::where('booking_id', $b->id)->value('amount') ?? 'N/A';
        return $b;
    });

    // Generate HTML content
    $htmlContent = "";
    foreach ($bookings as $booking) {
        $htmlContent .= view('demand_note', compact('booking'))->render();
    }

    // Generate PDF
    $pdf = Pdf::loadHTML($htmlContent);
    
    // Define file name
    $fileName = 'Bulk_Demand_Notes.pdf';

    // Return the PDF directly as a response (no need to store it)
    return response($pdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ]);
}





    public function downloadZip(){
        $files = Storage::disk('public')->files("pdfs");
        $zipFileName = "pdfs.zip";
        $zip = new \ZipArchive;
        $zip->open(storage_path("app/public/{$zipFileName}"))->addFile($files);
        return response()->download(storage_path("app/public/{$zipFileName}"))->deleteFileAfterSend(true);
    }
}
