<!DOCTYPE html>
<html>
<head>
    <title>Tous les bons de vente - {{ now()->format('Y-m-d') }}</title>
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
        <div class="logo-container">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Logo">
        </div>
        <div class="header-text">
            <h1>Tous les bons de vente</h1>
            <h3>
                @if(request()->has('date_from') || request()->has('date_to'))
                    Période du {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : 'début' }}
                    au {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') : 'aujourd\'hui' }}
                @else
                    Toutes les ventes
                @endif
            </h3>
            <h4>Généré le : {{ now()->format('d/m/Y H:i') }}</h4>
        </div>
    </div>

    @php $totalPages = count($sales); @endphp

    @foreach($sales as $index => $sale)
        <div class="document">
            <table>
                <tr>
                    <td width="50%"><strong>N° Bon :</strong> BV-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td width="50%"><strong>Date :</strong> {{ $sale->date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Client :</strong> {{ $sale->client->name }}</td>
                    <td><strong>Adresse de livraison :</strong> StockIno - 45 Av. Mohammed V, Rabat</td>
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
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Sous-total :</strong></td>
                        <td>{{ number_format($sale->quantity * $sale->price, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>TVA ({{ $sale->tax_rate ?? 0 }}%) :</strong></td>
                        <td>{{ number_format($sale->quantity * $sale->price * (($sale->tax_rate ?? 0)/100), 2) }} DH</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Montant total :</strong></td>
                        <td>{{ number_format($sale->quantity * $sale->price * (1 + ($sale->tax_rate ?? 0)/100), 2) }} DH</td>
                    </tr>
                </tbody>
            </table>

            <div class="page-number">
                Page {{ $index + 1 }}/{{ $totalPages }}
            </div>

            @if($loop->last)
                <div class="signature-block">
                    <div class="signature-info"><strong>Autorisation de l'entreprise :</strong></div>
                    <div class="signature-line"></div>
                    <div class="signature-info">Nom : _________________________</div>
                    <div class="signature-info">Rôle : Admin/Gestionnaire</div>
                    <div class="signature-info">Date : {{ now()->format('d/m/Y') }}</div>
                </div>
            @endif

            <div class="footer" style="font-size: 11px; color: #292929;">
                <p>
                    <i class="fas fa-building"></i> StockIno Magazine | 
                    <i class="fas fa-map-marker-alt"></i> 45 Av. Mohammed V, Rabat | 
                    <i class="fas fa-phone"></i> +212 5 37 22 33 44 | 
                    <i class="fas fa-envelope"></i> info@stockino.ma
                </p>
            </div>

            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        </div>
    @endforeach
</body>
</html>