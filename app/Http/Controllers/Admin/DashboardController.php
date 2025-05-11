<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\Client;

class DashboardController extends Controller
{
    public function index()
    {
        // User statistics
        $stats = [
            'total_users' => User::count(),
            'admin_count' => User::where('role', 'admin')->count(),
            'gestionnaire_count' => User::where('role', 'gestionnaire')->count(),
            'magasin_count' => User::where('role', 'magasin')->count(),

            // Product statistics
            'total_products' => Product::count(),
            'total_suppliers' => Supplier::count(),
            'total_clients' => Client::count(),
        ];

        // Calculate inventory status
        $productsWithStock = Product::with(['purchases', 'sales'])->get()->map(function ($product) {
            $totalPurchased = $product->purchases->sum('quantity');
            $totalSold = $product->sales->sum('quantity');
            $currentStock = $totalPurchased - $totalSold;

            return [
                'product' => $product,
                'stock' => $currentStock,
                'total_purchased' => $totalPurchased,
                'total_sold' => $totalSold
            ];
        });

        $stats['total_net_stock'] = $productsWithStock->sum('stock');

        // Calculate inventory counts
        $stats['in_stock_products'] = $productsWithStock->filter(fn($item) => $item['stock'] > 5)->count();
        $stats['low_stock_products'] = $productsWithStock->filter(fn($item) => $item['stock'] > 0 && $item['stock'] <= 5)->count();
        $stats['out_of_stock_products'] = $productsWithStock->filter(fn($item) => $item['stock'] <= 0)->count();

        // Total sales and purchases
        $stats['total_products_sold'] = $productsWithStock->sum('total_sold');
        $stats['total_sales_value'] = Sale::get()->sum(function ($sale) {
            return $sale->quantity * $sale->price;
        });

        $stats['total_products_purchased'] = $productsWithStock->sum('total_purchased');
        $stats['total_purchases_value'] = Purchase::get()->sum(function ($purchase) {
            return $purchase->quantity * $purchase->price;
        });

        // Recent activity (30 days)
        $stats['recent_sales_count'] = Sale::where('date', '>=', now()->subDays(30))->count();
        $stats['recent_sales_total'] = Sale::where('date', '>=', now()->subDays(30))->get()->sum(function ($sale) {
            return $sale->quantity * $sale->price;
        });

        $stats['recent_purchases_count'] = Purchase::where('date', '>=', now()->subDays(30))->count();
        $stats['recent_purchases_total'] = Purchase::where('date', '>=', now()->subDays(30))->get()->sum(function ($purchase) {
            return $purchase->quantity * $purchase->price;
        });

        // Top products
        $topSoldProducts = Sale::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(3)
            ->get();

        $topPurchasedProducts = Purchase::select('product_id', DB::raw('SUM(quantity) as total_purchased'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_purchased')
            ->take(3)
            ->get();

        // Out of stock products
        $outOfStockProducts = $productsWithStock
            ->filter(fn($item) => $item['stock'] <= 0)
            ->map(fn($item) => (object)[
                'product' => $item['product'],
                'quantity' => $item['stock']
            ]);

        // Low stock products
        $lowStockProducts = $productsWithStock
            ->filter(fn($item) => $item['stock'] > 0 && $item['stock'] <= 5)
            ->sortBy('stock')
            ->take(5)
            ->map(fn($item) => (object)[
                'product' => $item['product'],
                'quantity' => $item['stock']
            ]);

        // Charts data
        $salesChart = $this->generateYearlyChartData(Sale::class, 'Ventes');
        $purchasesChart = $this->generateYearlyChartData(Purchase::class, 'Achats');

        // Recent transactions
        $recentSales = Sale::with(['product', 'client'])
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        $recentPurchases = Purchase::with(['product', 'supplier'])
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'salesChart',
            'purchasesChart',
            'recentSales',
            'recentPurchases',
            'lowStockProducts',
            'outOfStockProducts',
            'topSoldProducts',
            'topPurchasedProducts'
        ));
    }

    protected function generateYearlyChartData($model, $label)
    {
        $months = collect();
        $data = collect();

        for ($i = 11; $i >= 0; $i--) {
            $startOfMonth = now()->subMonths($i)->startOfMonth();
            $endOfMonth = now()->subMonths($i)->endOfMonth();

            $monthName = $startOfMonth->format('M Y');
            $months->push($monthName);

            $count = $model::whereBetween('date', [$startOfMonth, $endOfMonth])->count();
            $data->push($count);
        }

        return [
            'labels' => $months,
            'data' => $data,
            'label' => $label
        ];
    }
}
