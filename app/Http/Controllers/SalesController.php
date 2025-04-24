<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Http\Request;


class SalesController extends Controller
{
    //

    public function index()
    {
        $sales = Sale::with(['product', 'client'])->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::all();
        $clients = Client::all();
        return view('sales.create', compact('products', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'client_id' => 'required|exists:clients,id',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        Sale::create($request->all());

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully.');
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
