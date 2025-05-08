<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order PO-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .company-info img {
            max-width: 150px; /* Resize logo to 150px */
            max-height: 150px;
        }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 8px; border: 1px solid #ddd; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th, .items-table td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        .total { text-align: right; font-weight: bold; margin-top: 10px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; }
        .signature-block {
            margin-top: 50px;
            text-align: left;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 50%;
            margin-top: 25px;
            padding-top: 5px;
        }
        .signature-info {
            margin: 12px 0;
        }
    </style>
</head>
<body>
    <div class="company-info">
        <img src="{{ public_path('path/to/your/logo.png') }}" alt="Company Logo">
        <div style="text-align: right;">
            <strong>Company Name</strong><br>
            Company Address<br>
            Phone: 123-456-7890<br>
            Email: info@company.com
        </div>
    </div>

    <div class="header">
        <h1>Purchase Order</h1>
        <h2>PO-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</h2>
        <p>Date: {{ $purchase->date->format('d/m/Y') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%"><strong>Supplier:</strong><br>{{ $purchase->supplier->name }}<br>{{ $purchase->supplier->address }}</td>
            <td width="50%"><strong>Delivery Address:</strong><br>Our Company Address</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $purchase->product->name }}</td>
                <td>{{ $purchase->quantity }}</td>
                <td>{{ number_format($purchase->price, 2) }} DH</td>
                <td>{{ number_format($purchase->quantity * $purchase->price, 2) }} DH</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p>Total: {{ number_format($purchase->quantity * $purchase->price, 2) }} DH</p>
    </div>


    <div class="signature-block">
        <div class="signature-info"><strong>Company Authorization:</strong></div>
        <div class="signature-line"></div>
        <div class="signature-info">Name: _________________________</div>
        <div class="signature-info">Role: Admin/Gestionnaire</div>
        <div class="signature-info">Date: {{ now()->format('d/m/Y') }}</div>
    </div>

    <div class="footer">
        <p>Please deliver the above items by {{ $purchase->date->addDays(7)->format('d/m/Y') }}</p>
        <p>Company Name | Address | Phone | Email</p>
    </div>
</body>
</html>
