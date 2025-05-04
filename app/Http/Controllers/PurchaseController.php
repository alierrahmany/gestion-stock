<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['product', 'supplier'])
            ->latest();

        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        if ($request->filled('product')) {
            $query->where('product_id', $request->product);
        }

        if ($request->filled('date')) {
            $query->whereDate('purchase_date', $request->date);
        }

        $purchases = $query->paginate(10);
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'products', 'suppliers'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('purchases.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'buy_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $purchase = Purchase::create($validated);

            $product = Product::find($validated['product_id']);
            $product->increment('quantity', $validated['quantity']);

            DB::commit();

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating purchase: ' . $e->getMessage());
        }
    }

    public function edit(Purchase $purchase)
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('purchases.edit', compact('purchase', 'products', 'suppliers'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'buy_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $quantityDiff = $validated['quantity'] - $purchase->quantity;

            $purchase->update($validated);

            if ($purchase->product_id != $validated['product_id'] || $quantityDiff != 0) {
                // Revert old product stock
                $oldProduct = Product::find($purchase->product_id);
                $oldProduct->decrement('quantity', $purchase->quantity);

                // Update new product stock
                $newProduct = Product::find($validated['product_id']);
                $newProduct->increment('quantity', $validated['quantity']);
            }

            DB::commit();

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating purchase: ' . $e->getMessage());
        }
    }

    public function destroy(Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            $product = Product::find($purchase->product_id);
            $product->decrement('quantity', $purchase->quantity);

            $purchase->delete();

            DB::commit();

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting purchase: ' . $e->getMessage());
        }
    }
}
