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
            'report_type' => ['required', 'in:'.implode(',', $validTypes)],
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

        return view('reports.index', [
            'products' => $products,
            'transactions' => $transactions,
            'chartData' => $chartData,
            'topProducts' => $topProducts,
            'filters' => $request->all()
        ]);
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
        } elseif ($format === 'pdf') {
            return $this->exportToPdf($reportData);
        }

        return redirect()->back()->with('error', 'Invalid export format');
    }

    private function prepareChartData($type, $startDate, $endDate, $productId = null)
    {
        if ($type === 'sales') {
            $query = Sale::query();
        } else {
            $query = Purchase::query();
        }

        $query->whereNotNull('date')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $results = $query->selectRaw('
            DATE(date) as date,
            SUM(quantity * price) as total,
            COUNT(*) as count
        ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d');

            $found = $results->firstWhere('date', $dateStr);
            $data[] = $found ? (float)$found->total : 0;

            $currentDate->addDay();
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'currency' => 'DH'
        ];
    }

    private function getTopProductsData($type, $startDate, $endDate, $limit = 5)
    {
        if ($type === 'sales') {
            $query = Sale::with('product')
                ->whereNotNull('date')
                ->whereBetween('date', [$startDate, $endDate]);
        } else {
            $query = Purchase::with('product')
                ->whereNotNull('date')
                ->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->selectRaw('
                product_id,
                SUM(quantity) as total_quantity,
                SUM(quantity * price) as total_amount
            ')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name ?? 'Unknown Product',
                    'quantity' => $item->total_quantity,
                    'amount' => $item->total_amount
                ];
            });
    }

    private function exportToCsv($reportData)
    {
        $fileName = $reportData['report_type'] . '_report_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($reportData) {
            $file = fopen('php://output', 'w');

            // Report Header
            fputcsv($file, ['*** ' . strtoupper($reportData['report_type']) . ' REPORT ***']);
            fputcsv($file, ['Generated: ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($file, ['Date Range: ' . $reportData['filters']['start_date'] . ' to ' . $reportData['filters']['end_date']]);
            fputcsv($file, []); // Empty row

            // Summary Section
            fputcsv($file, ['=== SUMMARY ===']);

            $totalAmount = $reportData['transactions']->sum(function ($t) {
                return $t->quantity * $t->price;
            });
            $totalQuantity = $reportData['transactions']->sum('quantity');

            if ($reportData['report_type'] == 'sales') {
                fputcsv($file, ['Total Sales:', $reportData['transactions']->count()]);
                fputcsv($file, ['Total Revenue:', number_format($totalAmount, 2) . ' DH']);
                fputcsv($file, ['Items Sold:', $totalQuantity]);
            } else {
                fputcsv($file, ['Total Purchases:', $reportData['transactions']->count()]);
                fputcsv($file, ['Total Cost:', number_format($totalAmount, 2) . ' DH']);
                fputcsv($file, ['Items Purchased:', $totalQuantity]);
            }

            fputcsv($file, []); // Empty row

            // Transactions Header
            fputcsv($file, ['=== TRANSACTION DETAILS ===']);
            fputcsv($file, [
                'DATE',
                'REFERENCE',
                'PRODUCT',
                strtoupper($reportData['report_type'] == 'sales' ? 'CLIENT' : 'SUPPLIER'),
                'QTY',
                'UNIT PRICE',
                'TOTAL'
            ]);

            // Transaction Data
            foreach ($reportData['transactions'] as $transaction) {
                fputcsv($file, [
                    $transaction->date ? $transaction->date->format('d/m/Y') : 'N/A',
                    $transaction->reference ?? 'N/A',
                    $transaction->product->name ?? 'N/A',
                    $reportData['report_type'] == 'sales' ? ($transaction->client->name ?? 'N/A') : ($transaction->supplier->name ?? 'N/A'),
                    $transaction->quantity,
                    number_format($transaction->price, 2) . ' DH',
                    number_format($transaction->quantity * $transaction->price, 2) . ' DH'
                ]);
            }

            // Top Products Section
            if ($reportData['topProducts']->isNotEmpty()) {
                fputcsv($file, []);
                fputcsv($file, ['=== TOP ' . strtoupper($reportData['report_type'] == 'sales' ? 'SOLD' : 'PURCHASED') . ' PRODUCTS ===']);
                fputcsv($file, ['PRODUCT', 'QUANTITY', 'TOTAL AMOUNT']);

                foreach ($reportData['topProducts'] as $product) {
                    fputcsv($file, [
                        $product['name'],
                        $product['quantity'],
                        number_format($product['amount'], 2) . ' DH'
                    ]);
                }
            }

            // Footer
            fputcsv($file, []);
            fputcsv($file, ['*** END OF REPORT ***']);

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportToPdf($reportData)
    {
        $pdf = PDF::loadView('reports.pdf', [
            'reportData' => $reportData,
            'title' => ucfirst($reportData['report_type']) . ' Report',
            'dateRange' => $reportData['filters']['start_date'] . ' to ' . $reportData['filters']['end_date']
        ]);

        $fileName = $reportData['report_type'] . '_report_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }
}
