<!DOCTYPE html>
<html>
<head>
    <title>Bon de Livraison BL-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</title>
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
        .logo-container {
            width: 130px;
            height: 130px;
            margin-right: 25px;
        }
        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
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
        .header-text h2 {
            margin: 5px 0 0;
            font-size: 18px;
            color: #3a3a3a;
        }
        .header-text p {
            margin: 5px 0 0;
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
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #777;
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
        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Logo">
        </div>
        <div class="header-text">
            <h1>Bon de Livraison</h1>
            <h2>BL-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</h2>
            <p>Date : {{ $sale->date->format('d/m/Y') }}</p>
        </div>
    </div>

    <table>
        <tr>
            <td width="50%"><strong>Client :</strong><br>{{ $sale->client->name }}</td>
            <td width="50%"><strong>Adresse de livraison :</strong><br>{{ $sale->client->address }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $sale->product->name }}</td>
                <td>{{ $sale->quantity }}</td>
                <td>{{ number_format($sale->price, 2) }} DH</td>
                <td>{{ number_format($sale->quantity * $sale->price, 2) }} DH</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p>Total : {{ number_format($sale->quantity * $sale->price, 2) }} DH</p>
    </div>

    <div class="signature-block">
        <div class="signature-info"><strong>Autorisation de l'entreprise :</strong></div>
        <div class="signature-line"></div>
        <div class="signature-info">Nom : _________________________</div>
        <div class="signature-info">Rôle : Admin/Gestionnaire</div>
        <div class="signature-info">Date : {{ now()->format('d/m/Y') }}</div>
    </div>

    <div class="footer" style="font-size: 11px; color: #292929;">
        <p>
            <i class="fas fa-building"></i> StockIno Magazine | 
            <i class="fas fa-map-marker-alt"></i> 45 Av. Mohammed V, Rabat | 
            <i class="fas fa-phone"></i> +212 5 37 22 33 44 | 
            <i class="fas fa-envelope"></i> info@stockino.ma
        </p>
    </div>
</body>
</html>