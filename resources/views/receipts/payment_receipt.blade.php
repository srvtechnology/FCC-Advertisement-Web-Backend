<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: url('{{asset('storage/image4.png')}}') no-repeat center center;
            background-size: cover;
        }
      .container {
        width: 80%;
        margin: auto;
        color: #1e398d;
        }
       .header {
        font-size: 24px; /* Reduced header font size */
        font-weight: 900;
        color: #1e398d;
        }
        .sub-header {
        font-size: 16px; /* Reduced sub-header font size */
        font-weight: 700;
        color: #1e398d;
        margin-bottom: 15px;
        }
        .table-container {
            border: 2px solid #1e398d;
            width: 100%;
            border-collapse: collapse;
            margin: auto;
            color: black;
            text-align: left;
        }
        th{
             font-size: 10px;
        }
        td {
            padding: 10px;
            vertical-align: top;
            font-size: 10px;
            font-weight: bold;
            text-align: left;
        }
        .logo {
            width: 60px;
            display: block;
        }
        .footer {
            font-size: 10px;
            margin-top: 10px;
            font-weight: bold;
            color: #1e398d;
            text-align: left;
        }
        a {
            color: #1e398d;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
        <div class="container" style="text-align: center;">
            <div class="header" style="font-weight:900; font-size:30px ;">FREETOWN CITY COUNCIL</div>
            <div>
                <img src="{{storage_path('app/public/image4.png')}}" class="logo">
               
            </div>
            <span><span class="bold" style="text-align:center;font-size: 12px;"><b>New City Council Complex
            17 Wallace-Johnson Street
            Freetown
        Sierra Leone</b></span>
        <br>
        Tel:<span class="bold" style="text-align:center;font-size: 12px;"> +232 76 345 504</span><br>
    </div>
    <hr>
     <div class="header" style="text-align:center;">Payment Receipt</div>
     <br>

   <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <tr>
        <th style="text-align: left;">Receipt ID</th>
        <td>FCC/PMT/{{ $payment->id }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Payment Created By User Name</th>
        <td>{{ $payment->createdByUser->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Payment Created By User Name</th>
        <td>{{ $payment->createdByUser->id ?? 'N/A' }}</td>
    </tr>



    <tr>
        <th style="text-align: left;">Customer Name</th>
        <td>{{ $payment->booking->customer_name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Customer Email</th>
        <td>{{ $payment->booking->customer_email ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Customer Phone</th>
        <td>{{ $payment->booking->customer_phone ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Space Category</th>
        <td>{{ $payment->space->category->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Space Location</th>
        <td>{{ $payment->space->location ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Amount Paid</th>
<td> 
    NLe 
    {{ number_format($payment->amount, fmod($payment->payment_amount_1, 1) ? 2 : 0, '.', ',') }}
</td>

    </tr>
    <tr>
        <th style="text-align: left;">Payment Mode</th>
        <td>{{ ucfirst($payment->payment_mode ?? 'N/A') }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Payee Name</th>
        <td>{{ $payment->payee_name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Payee Address</th>
        <td>{{ $payment->payee_address ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Bank Transaction ID</th>
        <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Payment Date</th>
        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Payment Status</th>
        <td>{{ ucfirst($payment->status) }}</td>
    </tr>
     <tr>
        <th style="text-align: left;">Payment Type</th>
        <td>{{ ucfirst($payment->payment_type) }}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Payment Proof</th>
        <td>
            @if(!empty($payment->payment_slip))
               {{--  <img src="{{ storage_path('app/public/' . $payment->payment_slip) }}" alt="Payment Slip" style="width: 100px;"> --}}
                <br>
              Payment Slip Url: {{url('/')}}/storage/{{$payment->payment_slip}}
            @else
                No payment slip uploaded.
            @endif
        </td>
    </tr>
</table>

    <p>Thank you for your payment!</p>

    <div style="display: flex; flex-direction: column; align-items: flex-end; text-align: right;">
    <img src="{{ storage_path('app/public/Stamp.png') }}" class="logo" style="width: 80px; display: block;">
    <img src="{{ storage_path('app/public/Sign.png') }}" class="logo" style="width: 60px; margin-top: -20px;">
      <p style="text-align:right; font-size:12px">Authorize Signature</p>
</div>

</body>
</html>
