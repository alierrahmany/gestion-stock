<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

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
            'price' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Calculate available stock
            $totalPurchased = Purchase::where('product_id', $validated['product_id'])
                                    ->sum('quantity');
            
            $totalSold = Sale::where('product_id', $validated['product_id'])
                            ->sum('quantity');
            
            $availableStock = $totalPurchased - $totalSold;

            if ($availableStock < $validated['quantity']) {
                return back()->withInput()
                            ->with('error', 'Insufficient stock. Available: ' . $availableStock);
            }

            // Create the sale
            $sale= Sale::create($validated);

            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => 'Sale #' . $sale->id . ' created for ' . $sale->client->name,
                'read' => false,
                'type' => 'sale'
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                            ->with('success', 'Sale created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Sale $sale)
    {
        $products = Product::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        
        // Calculate available stock including current sale quantity
        $totalPurchased = Purchase::where('product_id', $sale->product_id)
                                ->sum('quantity');
        $totalSold = Sale::where('product_id', $sale->product_id)
                        ->where('id', '!=', $sale->id)
                        ->sum('quantity');
        $availableStock = $totalPurchased - $totalSold;

        return view('sales.edit', compact('sale', 'products', 'clients', 'availableStock'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'client_id' => 'required|exists:clients,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $quantityDiff = $validated['quantity'] - $sale->quantity;

            if ($quantityDiff > 0) { // If increasing quantity
                $totalPurchased = Purchase::where('product_id', $validated['product_id'])
                                        ->sum('quantity');
                $totalSold = Sale::where('product_id', $validated['product_id'])
                                ->sum('quantity');
                $availableStock = $totalPurchased - $totalSold;

                if ($quantityDiff > $availableStock) {
                    return back()->withInput()
                                ->with('error', 'Insufficient stock for this increase. Available: ' . $availableStock);
                }
            }

            $sale->update($validated);

            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => 'Sale #' . $sale->id . ' Updated for ' . $sale->client->name,
                'read' => false,
                'type' => 'sale'
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                            ->with('success', 'Sale updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Sale $sale)
    {
        DB::beginTransaction();
        try {
            $sale->delete();

            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => 'Sale #' . $sale->id . ' Deleted for ' . $sale->client->name,
                'read' => false,
                'type' => 'sale'
            ]);
            DB::commit();
            return redirect()->route('sales.index')
                            ->with('success', 'Sale deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getAvailableStock($productId)
    {
        $totalPurchased = Purchase::where('product_id', $productId)
                                ->sum('quantity');
        $totalSold = Sale::where('product_id', $productId)
                        ->sum('quantity');
        $availableStock = $totalPurchased - $totalSold;
        
        return response()->json(['stock' => $availableStock]);
    }
}