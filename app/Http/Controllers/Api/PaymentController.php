<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    // public function index(Request $request)
    // {
    //     // dd($request->all());
    //     $query = Payment::with('booking', 'space.category')->orderBy('id', 'desc');
    //     return response()->json($query->paginate(10));
    // }
    //ALTER TABLE `payments` ADD `payment_type` ENUM('full','partial') NULL AFTER `amount`, ADD `payment_amount_1` DECIMAL(10,2) NULL AFTER `payment_type`, ADD `payment_amount_2` DECIMAL(10,2) NULL AFTER `payment_amount_1`;

public function index(Request $request)
{
    $query = Payment::with('booking','createdByUser', 'space.category')->orderBy('id', 'desc');

    // Filter by Agent Name if provided
    if ($request->filled('agent_name')) {
        $query->whereHas('space', function ($q) use ($request) {
            $q->where('name_of_advertise_agent_company_or_person', 'LIKE', '%' . $request->agent_name . '%');
        });
    }

     if($request->filled('selectedBookId')) {
            $query->where('booking_id',$request->selectedBookId);
        }


    if ($request->filled('start_date') && $request->filled('end_date')) { 
        $fromDate = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s'); // Start of the day
        $toDate = Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s'); // End of the day

        $query->whereBetween('payment_date', [$fromDate, $toDate]);
    }

    return response()->json($query->paginate(50));
}



    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'booking_id' => 'required|exists:bookings,id',
    //         'space_id' => 'required|exists:spaces,id',
    //         'amount' => 'required|numeric|min:1',
    //     ]);

    //     $payment = Payment::create([
    //         'booking_id' => $request->booking_id,
    //         'space_id' => $request->space_id,
    //         'amount' => $request->amount,
    //         'status' => 'completed',
    //     ]);
    //     Booking::where('id', $request->booking_id)->update(['status' => 'approved']);

    //     return response()->json(['message' => 'Payment successful', 'payment' => $payment]);
    // }

    public function store(Request $request)
{
    $request->validate([
        'booking_id' => 'required|exists:bookings,id',
        'space_id' => 'required|exists:spaces,id',
        'amount' => 'required|numeric|min:1',
        'payee_name' => 'required|string',
        'payment_date' => 'required|date',
        'payment_mode' => 'required|string',
        'payee_address' => 'required|string',
        'transaction_id' => 'required|string',
        'payment_slip' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        // 'x'=>'required',
    ]);

    // Handle file upload
    $paymentSlipPath = null;
    if ($request->hasFile('payment_slip')) {
        $paymentSlipPath = $request->file('payment_slip')->store('payment_slips', 'public');
    }

    //chk booking_id
    $chk=Payment::where('booking_id',$request->booking_id)->first();
    if($chk){
        //2nd pay 
         $payment = Payment::create([
        'booking_id' => $request->booking_id,
        'space_id' => $request->space_id,
        'amount' => $request->amount,
        'status' => 'completed',
        'payee_name' => $request->payee_name,
        'payment_date' => $request->payment_date,
        'payment_mode' => $request->payment_mode,
        'payee_address' => $request->payee_address,
        'transaction_id' => $request->transaction_id,
        'payment_slip' => $paymentSlipPath,
        'payment_type' => $request->payment_type,
        'payment_amount_1' => $request->paymentamount,
    ]);
    }else{
        //1st pay
         $payment = Payment::create([
        'booking_id' => $request->booking_id,
        'space_id' => $request->space_id,
        'amount' => $request->amount,
        'status' => 'completed',
        'payee_name' => $request->payee_name,
        'payment_date' => $request->payment_date,
        'payment_mode' => $request->payment_mode,
        'payee_address' => $request->payee_address,
        'transaction_id' => $request->transaction_id,
        'payment_slip' => $paymentSlipPath,
        'payment_type' => $request->payment_type,
        'payment_amount_1' => $request->paymentamount,
    ]);


    }

   
    // Update booking status to approved
    Booking::where('id', $request->booking_id)->update(['status' => 'approved']);

    return response()->json(['message' => 'Payment successful', ]);
}


    public function show($id)
    {
        $payment = Payment::with(['booking', 'space.category','createdByUser'])->find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return response()->json($payment);
    }

    public function downloadExcel($id)
    {
        $payment = Payment::with(['booking', 'space.category'])->find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return Excel::download(new PaymentExport($payment), "Payment_Details_{$id}.xlsx");
    }


    public function downloadReceipt($id)
    {
        $payment = Payment::with('booking', 'space.category','createdByUser')->findOrFail($id);

        $pdf = Pdf::loadView('receipts.payment_receipt', compact('payment'));

        return $pdf->download("Payment_Receipt_{$id}.pdf");
    }
}
