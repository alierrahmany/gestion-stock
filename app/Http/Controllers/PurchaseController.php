<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['product', 'supplier'])->latest();

        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        if ($request->filled('product')) {
            $query->where('product_id', $request->product);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date); // Changed from purchase_date to date
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
            'price' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);
    
        DB::beginTransaction();
        try {
            // Debug: Check what data is being received
            Log::info('Creating purchase with data:', $validated);
    
            // Create the purchase with explicit field mapping
            $purchase = new Purchase();
            $purchase->supplier_id = $validated['supplier_id'];
            $purchase->product_id = $validated['product_id'];
            $purchase->quantity = $validated['quantity'];
            $purchase->price = $validated['price'];
            $purchase->date = $validated['date'];
            $purchase->save();
    
            // Debug: Check if purchase was created
            Log::info('Purchase created:', $purchase->toArray());
    
            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => 'Purchase #' . $purchase->id . ' from ' . $purchase->supplier->name,
                'read' => false,
                'type' => 'purchase'
            ]);
    
            DB::commit();
    
            return redirect()->route('purchases.index')
                ->with('success', 'Purchase created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase creation failed: ' . $e->getMessage());
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
            'price' => 'required|numeric|min:0.01',  // Changed from buy_price to price
            'date' => 'required|date',              // Changed from purchase_date to date
        ]);

        DB::beginTransaction();
        try {
            $quantityDiff = $validated['quantity'] - $purchase->quantity;

            // Update with correct field names
            $purchase->update([
                'supplier_id' => $validated['supplier_id'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],     // Changed from buy_price to price
                'date' => $validated['date'],       // Changed from purchase_date to date
            ]);

            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => 'Purchase #' . $purchase->id . ' Updated from ' . $purchase->supplier->name,
                'read' => false,
                'type' => 'purchase'
            ]);

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

            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => 'Purchase #' . $purchase->id . ' Deleted from ' . $purchase->supplier->name,
                'read' => false,
                'type' => 'purchase'
            ]);

            DB::commit();

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting purchase: ' . $e->getMessage());
        }
    }
}
