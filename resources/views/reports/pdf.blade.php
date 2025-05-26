<!DOCTYPE html>
<html>
<head>
    <title>Rapport {{ $title }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
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
            width: 100px;
            height: 100px;
            margin-right: 20px;
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
            font-size: 20px;
            color: #2c3e50;
            text-transform: uppercase;
        }
        .header-text h2 {
            margin: 5px 0 0;
            font-size: 16px;
            color: #3a3a3a;
            font-weight: normal;
        }
        .header-text p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        .info-box {
            display: flex;
            margin-bottom: 15px;
        }
        .info-box div {
            flex: 1;
            padding: 10px;
            border: 1px solid #eee;
            margin-right: 10px;
        }
        .info-box div:last-child {
            margin-right: 0;
        }
        .highlight-box {
            display: flex;
            margin-bottom: 15px;
        }
        .highlight-box > div {
            flex: 1;
            padding: 10px;
            border: 1px solid #eee;
            margin-right: 10px;
            background-color: #f9f9f9;
        }
        .highlight-box > div:last-child {
            margin-right: 0;
        }
        .highlight-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }
        th {
            background-color: #f5f5f5;
            text-align: left;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .signature-block {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            width: 45%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
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
            <h1>Rapport {{ $reportData['report_type'] == 'sales' ? 'des Ventes' : 'des Achats' }}</h1>
            <h2>StockIno - Gestion de Stock</h2>
            <p>Période : {{ $dateRange }} | Généré le : {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="info-box">
        <div>
            <strong>Total {{ $reportData['report_type'] == 'sales' ? 'Ventes' : 'Achats' }}:</strong> 
            {{ $reportData['transactions']->count() }}
        </div>
        <div>
            <strong>Total {{ $reportData['report_type'] == 'sales' ? 'Revenu' : 'Coût' }}:</strong> 
            {{ number_format($reportData['transactions']->sum(function($t) { return $t->quantity * $t->price; }), 2) }} DH
        </div>
        <div>
            <strong>Articles {{ $reportData['report_type'] == 'sales' ? 'Vendus' : 'Achetés' }}:</strong> 
            {{ $reportData['transactions']->sum('quantity') }}
        </div>
    </div>
    
    <div class="section-title">Détails des Transactions</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Référence</th>
                <th>Produit</th>
                <th>{{ $reportData['report_type'] == 'sales' ? 'Client' : 'Fournisseur' }}</th>
                <th>Qté</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['transactions'] as $transaction)
            <tr>
                <td>{{ $transaction->date ? $transaction->date->format('d/m/Y') : 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                    RP-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}
                </td>
                <td>{{ $transaction->product->name ?? 'N/A' }}</td>
                <td>
                    @if($reportData['report_type'] == 'sales')
                        {{ $transaction->client->name ?? 'N/A' }}
                    @else
                        {{ $transaction->supplier->name ?? 'N/A' }}
                    @endif
                </td>
                <td>{{ $transaction->quantity }}</td>
                <td>{{ number_format($transaction->price, 2) }} DH</td>
                <td>{{ number_format($transaction->quantity * $transaction->price, 2) }} DH</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4">Total Général</td>
                <td>{{ $reportData['transactions']->sum('quantity') }}</td>
                <td></td>
                <td>{{ number_format($reportData['transactions']->sum(function($t) { return $t->quantity * $t->price; }), 2) }} DH</td>
            </tr>
        </tbody>
    </table>
    
    <!-- Highlights Section for Both Sales and Purchases -->
    @if(!empty($highlights))
    <div class="section-title">{{ $highlights['title'] }}</div>
    <div class="highlight-box">
        <div>
            <div class="highlight-title">{{ $highlights['quantityTitle'] }}</div>
            @if(!empty($highlights['topByQuantity']))
                <p>
                    <strong>Produit:</strong> {{ $highlights['topByQuantity']['name'] }}<br>
                    <strong>Quantité:</strong> {{ $highlights['topByQuantity']['quantity'] }}<br>
                    <strong>Montant Total:</strong> {{ number_format($highlights['topByQuantity']['amount'], 2) }} DH
                </p>
            @else
                <p>Aucune donnée disponible</p>
            @endif
        </div>
        <br>
        <div>
            <div class="highlight-title">{{ $highlights['amountTitle'] }}</div>
            @if(!empty($highlights['topByAmount']))
                <p><strong>Produit:</strong> {{ $highlights['topByAmount']['name'] }}</p>
                <p><strong>Quantité:</strong> {{ $highlights['topByAmount']['quantity'] }}</p>
                <p><strong>Montant Total:</strong> {{ number_format($highlights['topByAmount']['amount'], 2) }} DH</p>
            @else
                <p>Aucune donnée disponible</p>
            @endif
        </div>
    </div>
    @endif
    
    <div class="signature-block">
        <div class="signature">
            <div class="signature-line"></div>
            <div>Signature du Responsable</div>
        </div>
        <div class="signature">
            <div class="signature-line"></div>
            <div>Cachet de l'Entreprise</div>
        </div>
    </div>
    
    <div class="footer">
        <p>
            <strong>StockIno</strong> | 45 Av. Mohammed V, Rabat | Tél: +212 5 37 22 33 44 | Email: info@stockino.ma
        </p>
        <p>Ce document est généré automatiquement et ne nécessite pas de signature manuscrite</p>
    </div>
</body>
</html>