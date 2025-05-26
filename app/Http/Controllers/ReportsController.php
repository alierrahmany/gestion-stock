<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('reports.index', compact('products'));
    }

    public function generate(Request $request)
    {
        $validTypes = [];

        if (Auth::user()->role === 'admin') {
            $validTypes = ['sales', 'purchases'];
        } elseif (Auth::user()->role === 'gestionnaire') {
            $validTypes = ['purchases'];
        } elseif (Auth::user()->role === 'magasin') {
            $validTypes = ['sales'];
        }

        $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_id' => 'nullable|exists:products,id',
            'report_type' => ['required', 'in:' . implode(',', $validTypes)],
        ]);

        $products = Product::all();
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        if ($request->report_type === 'sales') {
            $query = Sale::with(['product', 'client'])
                ->whereNotNull('date')
                ->whereBetween('date', [$startDate, $endDate]);
        } else {
            $query = Purchase::with(['product', 'supplier'])
                ->whereNotNull('date')
                ->whereBetween('date', [$startDate, $endDate]);
        }

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        $transactions = $query->orderBy('date')->paginate(15);

        $chartData = $this->prepareChartData($request->report_type, $startDate, $endDate, $request->product_id);
        $topProducts = $this->getTopProductsData($request->report_type, $startDate, $endDate, 5);
        $topProductsSummary = $this->prepareTopProducts($request->report_type, $startDate, $endDate, $request->product_id);

        return view('reports.index', [
            'products' => $products,
            'transactions' => $transactions,
            'chartData' => $chartData,
            'topProducts' => $topProducts,
            'topProductsSummary' => $topProductsSummary,
            'filters' => $request->all()
        ]);
    }

    private function prepareChartData($type, $startDate, $endDate, $productId = null)
    {
        if ($type === 'sales') {
            $query = Sale::query();
        } else {
            $query = Purchase::query();
        }

        $query->whereBetween('date', [$startDate, $endDate]);

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $dateDiff = $startDate->diffInDays($endDate);

        if ($dateDiff <= 31) {
            // Daily grouping
            $results = $query->selectRaw('
                DATE(date) as date_group,
                COALESCE(SUM(quantity * price), 0) as total_amount,
                COALESCE(SUM(quantity), 0) as total_quantity
            ')
                ->groupBy('date_group')
                ->orderBy('date_group')
                ->get();

            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $dateStr = $currentDate->format('Y-m-d');
                $labels[] = $currentDate->format('M d');

                $found = $results->firstWhere('date_group', $dateStr);
                $data[] = $found ? (float)$found->total_amount : 0;
                $quantities[] = $found ? (int)$found->total_quantity : 0;

                $currentDate->addDay();
            }
        } elseif ($dateDiff <= 365) {
            // Weekly grouping
            $results = $query->selectRaw('
                YEAR(date) as year,
                WEEK(date) as week,
                COALESCE(SUM(quantity * price), 0) as total_amount,
                COALESCE(SUM(quantity), 0) as total_quantity
            ')
                ->groupBy('year', 'week')
                ->orderBy('year')
                ->orderBy('week')
                ->get();

            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $year = $currentDate->format('Y');
                $week = $currentDate->weekOfYear;
                $labels[] = 'W' . $week . ' ' . $year;

                $found = $results->first(function ($item) use ($year, $week) {
                    return $item->year == $year && $item->week == $week;
                });

                $data[] = $found ? (float)$found->total_amount : 0;
                $quantities[] = $found ? (int)$found->total_quantity : 0;

                $currentDate->addWeek();
            }
        } else {
            // Monthly grouping
            $results = $query->selectRaw('
                YEAR(date) as year,
                MONTH(date) as month,
                COALESCE(SUM(quantity * price), 0) as total_amount,
                COALESCE(SUM(quantity), 0) as total_quantity
            ')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $year = $currentDate->format('Y');
                $month = $currentDate->month;
                $labels[] = $currentDate->format('M Y');

                $found = $results->first(function ($item) use ($year, $month) {
                    return $item->year == $year && $item->month == $month;
                });

                $data[] = $found ? (float)$found->total_amount : 0;
                $quantities[] = $found ? (int)$found->total_quantity : 0;

                $currentDate->addMonth();
            }
        }

        return [
            'labels' => $labels ?? [],
            'data' => $data ?? [],
            'quantities' => $quantities ?? [],
            'currency' => 'DH'
        ];
    }

    private function prepareTopProducts($type, $startDate, $endDate, $productId = null)
    {
        if ($type === 'sales') {
            $query = Sale::query();
        } else {
            $query = Purchase::query();
        }

        $query->whereBetween('date', [$startDate, $endDate]);

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $transactions = $query->with('product')->get();

        // Group by product and calculate totals
        $products = [];
        foreach ($transactions as $transaction) {
            $productId = $transaction->product_id;
            if (!isset($products[$productId])) {
                $products[$productId] = [
                    'name' => $transaction->product->name ?? 'Unknown Product',
                    'quantity' => 0,
                    'amount' => 0
                ];
            }
            $products[$productId]['quantity'] += $transaction->quantity;
            $products[$productId]['amount'] += ($transaction->quantity * $transaction->price);
        }

        // Convert to array and sort
        $products = array_values($products);

        // Find top by quantity
        usort($products, function ($a, $b) {
            return $b['quantity'] <=> $a['quantity'];
        });
        $topByQuantity = count($products) > 0 ? $products[0] : null;

        // Find top by amount
        usort($products, function ($a, $b) {
            return $b['amount'] <=> $a['amount'];
        });
        $topByAmount = count($products) > 0 ? $products[0] : null;

        return [
            'topByQuantity' => $topByQuantity,
            'topByAmount' => $topByAmount
        ];
    }

    private function getTopProductsData($type, $startDate, $endDate, $limit = 5)
    {
        if ($type === 'sales') {
            $query = Sale::with('product')
                ->whereBetween('date', [$startDate, $endDate]);
        } else {
            $query = Purchase::with('product')
                ->whereBetween('date', [$startDate, $endDate]);
        }

        $results = $query->selectRaw('
            product_id,
            SUM(quantity) as total_quantity,
            SUM(quantity * price) as total_amount
        ')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();

        // Filter out products with zero quantity or amount
        $filteredResults = $results->filter(function ($item) {
            return $item->total_quantity > 0 && $item->total_amount > 0;
        });

        return $filteredResults->map(function ($item) {
            return [
                'name' => $item->product->name ?? 'Unknown Product',
                'quantity' => (int)$item->total_quantity,
                'amount' => (float)$item->total_amount
            ];
        });
    }

    public function export(Request $request, $format)
    {
        $request->validate([
            'report_type' => 'required|in:sales,purchases',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_id' => 'nullable|exists:products,id'
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        if ($request->report_type === 'sales') {
            $query = Sale::with(['product', 'client'])
                ->whereNotNull('date')
                ->whereBetween('date', [$startDate, $endDate]);
        } else {
            $query = Purchase::with(['product', 'supplier'])
                ->whereNotNull('date')
                ->whereBetween('date', [$startDate, $endDate]);
        }

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        $transactions = $query->orderBy('date')->get();
        $topProducts = $this->getTopProductsData($request->report_type, $startDate, $endDate, 5);
        $highlights = $this->prepareHighlights($request->report_type, $startDate, $endDate, $request->product_id);
        $chartData = $this->prepareChartData($request->report_type, $startDate, $endDate, $request->product_id);

        $reportData = [
            'report_type' => $request->report_type,
            'transactions' => $transactions,
            'topProducts' => $topProducts,
            'filters' => [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'product_id' => $request->product_id
            ]
        ];

        if ($format === 'csv') {
            return $this->exportToCsv($reportData);
        }

        // Default to PDF
        $pdf = PDF::loadView('reports.pdf', [
            'reportData' => $reportData,
            'highlights' => $highlights,
            'title' => ucfirst($request->report_type) . ' Report',
            'dateRange' => $request->start_date . ' to ' . $request->end_date
        ]);

        return $pdf->download($request->report_type . '_report_' . now()->format('Ymd_His') . '.pdf');
    }

    private function prepareHighlights($type, $startDate, $endDate, $productId = null)
    {
        if ($type === 'sales') {
            $query = Sale::query();
            $title = "Points Forts des Ventes";
            $quantityTitle = "Produit le Plus Vendu (Quantité)";
            $amountTitle = "Produit le Plus Vendu (Valeur)";
        } else {
            $query = Purchase::query();
            $title = "Points Forts des Achats";
            $quantityTitle = "Produit le Plus Acheté (Quantité)";
            $amountTitle = "Produit le Plus Acheté (Valeur)";
        }

        $query->whereBetween('date', [$startDate, $endDate]);

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $transactions = $query->with('product')->get();

        $products = [];
        foreach ($transactions as $transaction) {
            $productId = $transaction->product_id;
            if (!isset($products[$productId])) {
                $products[$productId] = [
                    'name' => $transaction->product->name ?? 'Unknown Product',
                    'quantity' => 0,
                    'amount' => 0
                ];
            }
            $products[$productId]['quantity'] += $transaction->quantity;
            $products[$productId]['amount'] += ($transaction->quantity * $transaction->price);
        }

        $products = array_values($products);

        usort($products, function ($a, $b) {
            return $b['quantity'] <=> $a['quantity'];
        });
        $topByQuantity = count($products) > 0 ? $products[0] : null;

        usort($products, function ($a, $b) {
            return $b['amount'] <=> $a['amount'];
        });
        $topByAmount = count($products) > 0 ? $products[0] : null;

        return [
            'title' => $title,
            'quantityTitle' => $quantityTitle,
            'amountTitle' => $amountTitle,
            'topByQuantity' => $topByQuantity,
            'topByAmount' => $topByAmount
        ];
    }

    private function exportToCsv($reportData)
    {
        $fileName = $reportData['report_type'] . '_report_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($reportData) {
            $output = fopen('php://output', 'w');

            // Add UTF-8 BOM for perfect Excel compatibility
            fwrite($output, "\xEF\xBB\xBF");

            // 1. Company Header
            $this->writeCsvRow($output, ['StockIno - Gestion de Stock']);
            $this->writeCsvRow($output, ['45 Av. Mohammed V, Rabat | Tél: +212 5 37 22 33 44']);
            $this->writeCsvRow($output, []); // Empty row

            // 2. Report Title
            $reportTitle = $reportData['report_type'] == 'sales'
                ? 'Rapport des Ventes'
                : 'Rapport des Achats';
            $this->writeCsvRow($output, [$reportTitle]);
            $this->writeCsvRow($output, ['Période:', $reportData['filters']['start_date'] . ' au ' . $reportData['filters']['end_date']]);
            $this->writeCsvRow($output, ['Généré le:', now()->format('d/m/Y H:i')]);
            $this->writeCsvRow($output, []);

            // 3. Summary Section
            $this->writeCsvRow($output, ['RÉSUMÉ']);

            $totalAmount = $reportData['transactions']->sum(function ($t) {
                return $t->quantity * $t->price;
            });
            $totalQuantity = $reportData['transactions']->sum('quantity');

            if ($reportData['report_type'] == 'sales') {
                $this->writeCsvRow($output, ['Ventes totales', $reportData['transactions']->count()]);
                $this->writeCsvRow($output, ['Revenu total', $this->formatMoney($totalAmount)]);
                $this->writeCsvRow($output, ['Articles vendus', $totalQuantity]);
            } else {
                $this->writeCsvRow($output, ['Achats totaux', $reportData['transactions']->count()]);
                $this->writeCsvRow($output, ['Coût total', $this->formatMoney($totalAmount)]);
                $this->writeCsvRow($output, ['Articles achetés', $totalQuantity]);
            }
            $this->writeCsvRow($output, []);

            // 4. Highlights Section
            if (isset($reportData['topProducts']) && $reportData['topProducts']->isNotEmpty()) {
                $this->writeCsvRow($output, ['POINTS FORTS']);

                $highlightTitle = $reportData['report_type'] == 'sales'
                    ? 'Produits les plus vendus'
                    : 'Produits les plus achetés';
                $this->writeCsvRow($output, [$highlightTitle]);

                $this->writeCsvRow($output, ['Produit', 'Quantité', 'Montant total']);

                foreach ($reportData['topProducts'] as $product) {
                    $this->writeCsvRow($output, [
                        $product['name'],
                        $product['quantity'],
                        $this->formatMoney($product['amount'])
                    ]);
                }
                $this->writeCsvRow($output, []);
            }

            // 5. Transactions Section
            $this->writeCsvRow($output, ['DÉTAILS DES TRANSACTIONS']);
            $this->writeCsvRow($output, [
                'Date',
                'Référence',
                'Produit',
                $reportData['report_type'] == 'sales' ? 'Client' : 'Fournisseur',
                'Quantité',
                'Prix unitaire',
                'Total'
            ]);

            foreach ($reportData['transactions'] as $transaction) {
                $this->writeCsvRow($output, [
                    $transaction->date ? $transaction->date->format('d/m/Y') : 'N/A',
                    'RP-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT),
                    $transaction->product->name ?? 'N/A',
                    $reportData['report_type'] == 'sales'
                        ? ($transaction->client->name ?? 'N/A')
                        : ($transaction->supplier->name ?? 'N/A'),
                    $transaction->quantity,
                    $this->formatMoney($transaction->price),
                    $this->formatMoney($transaction->quantity * $transaction->price)
                ]);
            }

            // 6. Footer
            $this->writeCsvRow($output, []);
            $this->writeCsvRow($output, ['Généré par:', Auth::user()->name]);
            $this->writeCsvRow($output, ['StockIno - Système de gestion']);

            fclose($output);
        };

        return Response::stream($callback, 200, $headers);
    }

    // Helper method for consistent CSV writing
    private function writeCsvRow($handle, $fields)
    {
        fputcsv($handle, $fields, ',', '"', '\\');
    }

    // Helper method for money formatting
    private function formatMoney($amount)
    {
        return number_format($amount, 2, ',', ' ') . ' DH';
    }
    private function exportToPdf($reportData)
    {
        $pdf = PDF::loadView('reports.pdf', [
            'reportData' => $reportData,
            'title' => ucfirst($reportData['report_type']) . ' Report',
            'dateRange' => $reportData['filters']['start_date'] . ' to ' . $reportData['filters']['end_date'],
        ]);

        $fileName = $reportData['report_type'] . '_report_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }
}
