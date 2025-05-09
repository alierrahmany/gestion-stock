<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            
            // Product statistics - we'll calculate these differently
            'total_products' => Product::count(),
        ];

        // Calculate inventory status by getting all products with their stock levels
        $productsWithStock = Product::with(['purchases', 'sales'])->get()->map(function($product) {
            $totalPurchased = $product->purchases->sum('quantity');
            $totalSold = $product->sales->sum('quantity');
            $currentStock = $totalPurchased - $totalSold;
            
            return [
                'product' => $product,
                'stock' => $currentStock
            ];
        });

        // Calculate inventory counts
        $stats['in_stock_products'] = $productsWithStock->filter(fn($item) => $item['stock'] > 5)->count();
        $stats['low_stock_products'] = $productsWithStock->filter(fn($item) => $item['stock'] > 0 && $item['stock'] <= 5)->count();
        $stats['out_of_stock_products'] = $productsWithStock->filter(fn($item) => $item['stock'] <= 0)->count();
        
        // Sales statistics
        $stats['recent_sales_count'] = Sale::where('date', '>=', now()->subDays(30))->count();
        $stats['recent_sales_total'] = Sale::where('date', '>=', now()->subDays(30))->get()->sum('total_amount');
        
        // Purchase statistics
        $stats['recent_purchases_count'] = Purchase::where('date', '>=', now()->subDays(30))->count();
        $stats['recent_purchases_total'] = Purchase::where('date', '>=', now()->subDays(30))->get()->sum('total_amount');

        // Sales chart data (last 30 days)
        $salesChart = $this->generateSalesChartData();
        
        // Recent data
        $recentSales = Sale::with(['product', 'client'])
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
            
        $recentPurchases = Purchase::with(['product', 'supplier'])
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
            
        // Get low stock products (stock <= 5)
        $lowStockProducts = $productsWithStock
            ->filter(fn($item) => $item['stock'] > 0 && $item['stock'] <= 5)
            ->sortBy('stock')
            ->take(5)
            ->map(fn($item) => (object)[
                'product' => $item['product'],
                'quantity' => $item['stock']
            ]);

        return view('admin.dashboard', compact(
            'stats',
            'salesChart',
            'recentSales',
            'recentPurchases',
            'lowStockProducts'
        ));
    }

    protected function generateSalesChartData()
    {
        $dates = collect();
        $data = collect();
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->push(now()->subDays($i)->format('d M'));
            
            $count = Sale::whereDate('date', $date)->count();
            $data->push($count);
        }
        $data = $data->toArray();
        $maxSales = max($data);
        $peakDayIndex = array_search($maxSales, $data);
        return [
            'labels' => $dates,
            'data' => $data,
            'max_sales' => $maxSales,
            'peak_day' => $dates[$peakDayIndex] ?? 'N/A'
        ];
    }
}