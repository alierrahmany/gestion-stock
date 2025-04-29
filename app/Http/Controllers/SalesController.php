<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['product', 'client']);
        $products = Product::all();
        $clients = Client::all();

        // Filter by product
        if ($request->has('product') && $request->product !== '') {
            $query->where('product_id', $request->product);
        }

        // Filter by client
        if ($request->has('client') && $request->client !== '') {
            $query->where('client_id', $request->client);
        }

        // Filter by date range
        if ($request->has('date_start') && $request->date_start !== '') {
            $query->whereDate('date', '>=', $request->date_start);
        }
        if ($request->has('date_end') && $request->date_end !== '') {
            $query->whereDate('date', '<=', $request->date_end);
        }

        // Search by product name or client name
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })->orWhereHas('client', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            });
        }

        $sales = $query->latest()->paginate(10);
        return view('sales.index', compact('sales', 'products', 'clients'));
    }

    public function create()
    {
        $products = Product::all();
        $clients = Client::all();
        return view('sales.create', compact('products', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'client_id' => 'required|exists:clients,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Get the product
            $product = Product::findOrFail($validated['product_id']);

            // Check if enough stock
            if ($product->quantity < $validated['quantity']) {
                return back()->withErrors(['quantity' => 'Stock insuffisant pour ce produit.'])->withInput();
            }

            // Calculate total price
            $total_price = $product->price * $validated['quantity'];

            // Create sale
            $sale = Sale::create([
                'product_id' => $validated['product_id'],
                'client_id' => $validated['client_id'],
                'quantity' => $validated['quantity'],
                'total_price' => $total_price,
                'date' => $validated['date'],
            ]);

            // Update product quantity
            $product->quantity -= $validated['quantity'];
            $product->save();

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Vente enregistrée avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement de la vente.'])->withInput();
        }
    }

    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $products = Product::all();
        $clients = Client::all();
        return view('sales.edit', compact('sale', 'products', 'clients'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'client_id' => 'required|exists:clients,id',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $sale->update($request->all());

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}
