<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateBulkPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $booking) {
            $booking = json_decode(json_encode($booking)); // Convert array to object
            // dd($booking);
            $pdf = Pdf::loadView('demand_note', compact('booking'));
            $filename = 'document_' . $booking->id . '.pdf'; 
            Storage::disk('public')->put("pdfs/{$filename}", $pdf->output()); // Store in public disk, in a user specific folder.
        }
    }
}
