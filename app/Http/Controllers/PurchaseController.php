<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query with relationships
        $query = Purchase::with(['product', 'supplier']);
        
        // Get all products and suppliers for filters
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        // Apply filters
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        if ($request->filled('product')) {
            $query->where('product_id', $request->product);
        }

        if ($request->filled('date')) {
            $query->whereDate('purchase_date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                  ->orWhereHas('product', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('supplier', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Get paginated results
        $purchases = $query->latest()->paginate(10)->withQueryString();

        // Return view with all necessary data
        return view('purchases.index', compact('purchases', 'products', 'suppliers'));
    }

    // ...rest of the controller methods...
}