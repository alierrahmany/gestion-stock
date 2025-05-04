<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['product', 'client'])->latest();
        $products = Product::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();

        if ($request->filled('product')) {
            $query->where('product_id', $request->product);
        }

        if ($request->filled('client')) {
            $query->where('client_id', $request->client);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $sales = $query->paginate(10)->withQueryString();

        return view('sales.index', compact('sales', 'products', 'clients'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        return view('sales.create', compact('products', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'client_id' => 'required|exists:clients,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',  // Matches database column
            'date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $availableStock = Purchase::where('product_id', $validated['product_id'])
                ->sum('quantity');

            if ($availableStock < $validated['quantity']) {
                return back()->withInput()->with('error', 'Insufficient stock. Available: ' . $availableStock);
            }

            Sale::create([
                'product_id' => $validated['product_id'],
                'client_id' => $validated['client_id'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],  // Matches database column
                'date' => $validated['date'],
            ]);

            // FIFO stock deduction
            $remaining = $validated['quantity'];
            $purchases = Purchase::where('product_id', $validated['product_id'])
                ->orderBy('purchase_date')
                ->get();

            foreach ($purchases as $purchase) {
                if ($remaining <= 0) break;
                $deduct = min($remaining, $purchase->quantity);
                $purchase->decrement('quantity', $deduct);
                $remaining -= $deduct;
            }

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Sale created!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Sale $sale)
    {
        $products = Product::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        $availableStock = Purchase::where('product_id', $sale->product_id)
            ->sum('quantity') + $sale->quantity;

        return view('sales.edit', compact('sale', 'products', 'clients', 'availableStock'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'client_id' => 'required|exists:clients,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',  // Matches database column
            'date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $quantityDiff = $validated['quantity'] - $sale->quantity;
            $availableStock = Purchase::where('product_id', $validated['product_id'])
                ->sum('quantity');

            if ($quantityDiff > 0 && $availableStock < $quantityDiff) {
                return back()->withInput()->with('error', 'Insufficient stock. Available: ' . $availableStock);
            }

            $sale->update([
                'product_id' => $validated['product_id'],
                'client_id' => $validated['client_id'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],  // Matches database column
                'date' => $validated['date'],
            ]);

            // Handle stock changes
            if ($quantityDiff != 0) {
                if ($quantityDiff > 0) {
                    // Deduct additional quantity
                    $remaining = $quantityDiff;
                    $purchases = Purchase::where('product_id', $validated['product_id'])
                        ->orderBy('purchase_date')
                        ->get();

                    foreach ($purchases as $purchase) {
                        if ($remaining <= 0) break;
                        $deduct = min($remaining, $purchase->quantity);
                        $purchase->decrement('quantity', $deduct);
                        $remaining -= $deduct;
                    }
                } else {
                    // Return quantity
                    $remaining = abs($quantityDiff);
                    $purchases = Purchase::where('product_id', $validated['product_id'])
                        ->orderBy('purchase_date', 'desc')
                        ->get();

                    foreach ($purchases as $purchase) {
                        if ($remaining <= 0) break;
                        $add = $remaining;
                        $purchase->increment('quantity', $add);
                        $remaining -= $add;
                    }
                }
            }

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Sale updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Sale $sale)
    {
        DB::beginTransaction();
        try {
            // Return quantity to purchases
            $remaining = $sale->quantity;
            $purchases = Purchase::where('product_id', $sale->product_id)
                ->orderBy('purchase_date', 'desc')
                ->get();

            foreach ($purchases as $purchase) {
                if ($remaining <= 0) break;
                $add = $remaining;
                $purchase->increment('quantity', $add);
                $remaining -= $add;
            }

            $sale->delete();
            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Sale deleted!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getAvailableStock($productId)
    {
        $stock = Purchase::where('product_id', $productId)->sum('quantity');
        return response()->json(['stock' => $stock]);
    }
}
