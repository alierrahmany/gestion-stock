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
        return view('reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:sales,products',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $reportType = $request->report_type;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : null;

        if ($reportType === 'sales') {
            $query = Sale::with(['product', 'client']);
            
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
            
            $data = $query->get();
            $total = $data->sum(function($sale) {
                return $sale->qty * $sale->price;
            });
            
            return view('reports.sales', compact('data', 'total', 'startDate', 'endDate'));
        } else {
            $query = Product::with(['category', 'sale']);
            
            $data = $query->get();
            
            return view('reports.products', compact('data'));
        }
    }
}
