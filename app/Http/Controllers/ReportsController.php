<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('reports.sales', compact('products'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_id' => 'nullable|exists:products,id'
        ]);

        $query = Sale::with(['product', 'client'])
            ->whereBetween('date', [$request->start_date, $request->end_date]);

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        $sales = $query->get();
        $products = Product::all();

        return view('reports.sales', compact('sales', 'products'));
    }
}
