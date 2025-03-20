<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Payment Details</h2>
    <table>
        <tr><th>ID</th><td>{{ $payment->id }}</td></tr>
        <tr><th>Space ID</th><td>{{ $payment->space->id ?? 'N/A' }}</td></tr>
        <tr><th>Category</th><td>{{ $payment->space->category->name ?? 'N/A' }}</td></tr>
        <tr><th>Customer</th><td>{{ $payment->booking->customer_name ?? 'N/A' }}</td></tr>
        <tr><th>Email</th><td>{{ $payment->booking->customer_email ?? 'N/A' }}</td></tr>
        <tr><th>Mobile</th><td>{{ $payment->booking->mobile ?? 'N/A' }}</td></tr>
        <tr><th>Total Bill</th><td>{{ $payment->amount }}</td></tr>
        <tr><th>Amount Paid</th><td>{{ $payment->amount }}</td></tr>
        <tr><th>Status</th><td>{{ $payment->status }}</td></tr>
    </table>
</body>
</html>
