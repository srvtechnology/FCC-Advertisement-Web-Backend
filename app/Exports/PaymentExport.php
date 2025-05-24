<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentExport implements FromView
{
    protected $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    public function view(): View
    {
        return view('exports.payment', ['payment' => $this->payment]);
    }
}

