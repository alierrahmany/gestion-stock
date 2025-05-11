<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic statistics
        $stats = [
            'total_categories' => Category::count(),
            'total_products' => Product::count(),
            'total_suppliers' => Supplier::count(),
        ];

        // Calculate inventory status
        $productsWithStock = Product::with(['purchases', 'sales'])->get()->map(function ($product) {
            $totalPurchased = $product->purchases->sum('quantity');
            $totalSold = $product->sales->sum('quantity');
            $currentStock = $totalPurchased - $totalSold;

            return [
                'product' => $product,
                'stock' => $currentStock
            ];
        });

        $stats['total_products_purchased'] = Purchase::sum('quantity');
        $stats['total_categories_purchased'] = DB::table('purchases')
            ->join('products', 'purchases.product_id', '=', 'products.id')
            ->whereNotNull('products.categorie_id')
            ->sum('purchases.quantity');

        // Calculate inventory counts
        $stats['in_stock_products'] = $productsWithStock->filter(fn($item) => $item['stock'] > 5)->count();
        $stats['low_stock_products'] = $productsWithStock->filter(fn($item) => $item['stock'] > 0 && $item['stock'] <= 5)->count();
        $stats['out_of_stock_products'] = $productsWithStock->filter(fn($item) => $item['stock'] <= 0)->count();

        // Purchase statistics (last 30 days)
        $stats['recent_purchases_count'] = Purchase::where('date', '>=', now()->subDays(30))->count();
        $stats['recent_purchases_total'] = Purchase::where('date', '>=', now()->subDays(30))->get()->sum('total_amount');


        $stats['total_products_purchases_amount'] = Purchase::sum(DB::raw('quantity * price'));
        $stats['total_categories_purchases_amount'] = DB::table('purchases')
            ->join('products', 'purchases.product_id', '=', 'products.id')
            ->whereNotNull('products.categorie_id')
            ->select(DB::raw('SUM(purchases.quantity * purchases.price) as total'))
            ->value('total');

            
        // Top 3 products purchased (all time)
        $topPurchasedProducts = Purchase::select('product_id', DB::raw('SUM(quantity) as total_purchased'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_purchased')
            ->take(3)
            ->get();

        // Top 3 active suppliers (by total products delivered)
        $topSuppliers = Purchase::select('supplier_id', DB::raw('SUM(quantity) as total_delivered'))
            ->with('supplier')
            ->groupBy('supplier_id')
            ->orderByDesc('total_delivered')
            ->take(3)
            ->get();

        // Recent purchases (last 30 days)
        $recentPurchases = Purchase::with(['product', 'supplier'])
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // Low stock products
        $lowStockProducts = $productsWithStock
            ->filter(fn($item) => $item['stock'] > 0 && $item['stock'] <= 5)
            ->sortBy('stock')
            ->take(5)
            ->map(fn($item) => (object)[
                'product' => $item['product'],
                'quantity' => $item['stock']
            ]);

        // Out of stock products
        $outOfStockProducts = $productsWithStock
            ->filter(fn($item) => $item['stock'] <= 0)
            ->map(fn($item) => (object)[
                'product' => $item['product'],
                'quantity' => $item['stock']
            ]);

        // Yearly purchases chart data
        $purchasesChart = $this->generateYearlyChartData(Purchase::class, 'Achats');

        return view('gestionnaire.dashboard', compact(
            'stats',
            'purchasesChart',
            'recentPurchases',
            'lowStockProducts',
            'topPurchasedProducts',
            'topSuppliers',
            'outOfStockProducts',
            'stats'
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
