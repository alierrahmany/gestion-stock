<!DOCTYPE html>
<html>
<head>
    <title>All Purchase Orders - {{ now()->format('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            color: #333;
            padding: 15mm;
            margin: 0;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #ccc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo-placeholder {
            width: 120px;
            height: 120px;
            border: 2px dashed #bbb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 25px;
            font-size: 14px;
            color: #999;
        }
        .header-text {
            flex: 1;
            text-align: center;
        }
        .header-text h1 {
            margin: 0;
            font-size: 22px;
            color: #2c3e50;
        }
        .header-text h3 {
            margin: 5px 0 0;
            font-weight: normal;
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 13px;
        }
        th {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }
        .page-number {
            text-align: right;
            font-size: 11px;
            margin-top: 15px;
            color: #555;
        }
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
        .page-break {
            page-break-after: always;
        }
        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-placeholder">LOGO</div>
        <div class="header-text">
            <h1>All Purchase Orders</h1>
            <h3>Generated on: {{ now()->format('d/m/Y H:i') }}</h3>
        </div>
    </div>

    @php $totalPages = count($purchases); @endphp

    @foreach($purchases as $index => $purchase)
        <div class="document">
            <table>
                <tr>
                    <td width="50%"><strong>Purchase Order #:</strong> PO-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td width="50%"><strong>Date:</strong> {{ $purchase->date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Supplier:</strong> {{ $purchase->supplier->name }}</td>
                    <td><strong>Delivery Address:</strong> Our Company Address</td>
                </tr>
            </table>

            <table>
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
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Subtotal:</strong></td>
                        <td>{{ number_format($purchase->quantity * $purchase->price, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Tax ({{ $purchase->tax_rate ?? 0 }}%):</strong></td>
                        <td>{{ number_format($purchase->quantity * $purchase->price * (($purchase->tax_rate ?? 0)/100), 2) }} DH</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Total Amount:</strong></td>
                        <td>{{ number_format($purchase->quantity * $purchase->price * (1 + ($purchase->tax_rate ?? 0)/100), 2) }} DH</td>
                    </tr>
                </tbody>
            </table>

            <!-- Page number -->
            <div class="page-number">
                Page {{ $index + 1 }}/{{ $totalPages }}
            </div>

            @if($loop->last)
                <!-- Show signature block only on the last page -->
                <div class="signature-block">
                    <div class="signature-info"><strong>Company Authorization:</strong></div>
                    <div class="signature-line"></div>
                    <div class="signature-info">Name: _________________________</div>
                    <div class="signature-info">Role: Admin/Gestionnaire</div>
                    <div class="signature-info">Date: {{ now()->format('d/m/Y') }}</div>
                </div>
            @endif

            <div class="footer">
                <p>Company Name | Address | Phone | Email</p>
            </div>

            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        </div>
    @endforeach
</body>
</html>
