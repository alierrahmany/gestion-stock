<?php

namespace App\Http\Controllers\Magasin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Client;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic statistics
        $stats = [
            'total_products' => Product::count(),
            'total_clients' => Client::count(),
        ];

        // Today's sales details and calculations
        $todaySalesDetails = Sale::with(['product', 'client'])
            ->whereDate('date', today())
            ->orderBy('created_at', 'desc')
            ->get();

        // Set today's sales count and amount
        $stats['today_sales_count'] = $todaySalesDetails->count();
        $stats['today_sales_amount'] = $todaySalesDetails->sum(function($sale) {
            return $sale->quantity * $sale->price;
        });

        // Total sales values
        $allSales = Sale::get();
        $stats['total_sales_value'] = $allSales->sum(function($sale) {
            return $sale->quantity * $sale->price;
        });

        $stats['total_products_sold'] = $allSales->sum('quantity');

        // Top 3 clients - Fixed GROUP BY query
        $topClients = Client::select([
                'clients.id',
                'clients.name',
                'clients.email',
                'clients.contact',
                DB::raw('SUM(sales.quantity * sales.price) as total_spent')
            ])
            ->join('sales', 'sales.client_id', '=', 'clients.id')
            ->groupBy([
                'clients.id',
                'clients.name',
                'clients.email',
                'clients.contact'
            ])
            ->orderByDesc('total_spent')
            ->take(3)
            ->get();

        // Top 3 sold products
        $topSoldProducts = DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select([
                'products.id',
                'products.name',
                DB::raw('SUM(sales.quantity) as total_sold')
            ])
            ->groupBy(['products.id', 'products.name'])
            ->orderByDesc('total_sold')
            ->take(3)
            ->get();

        // Sales chart data (last 30 days)
        $salesChart = $this->generateSalesChartData();

        return view('magasin.dashboard', compact(
            'stats',
            'topClients',
            'topSoldProducts',
            'salesChart',
            'todaySalesDetails'
        ));
    }

    protected function generateSalesChartData()
    {
        $dates = collect();
        $data = collect();

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->push(now()->subDays($i)->format('d M'));

            $dailySales = Sale::whereDate('date', $date)->get();
            $amount = $dailySales->sum(function($sale) {
                return $sale->quantity * $sale->price;
            });
            $data->push($amount);
        }

        return [
            'labels' => $dates,
            'data' => $data,
        ];
    }
}
