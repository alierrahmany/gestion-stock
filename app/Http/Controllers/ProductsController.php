<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'file_name' => 'nullable|image|max:2048'
        ]);

        // Add current date
        $validated['date'] = now();

        if ($request->hasFile('file_name')) {
            $file = $request->file('file_name');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/products', $filename);
            $validated['file_name'] = 'products/' . $filename;
        }

        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'file_name' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('file_name')) {
            if ($product->file_name) {
                Storage::delete('public/' . $product->file_name);
            }
            $file = $request->file('file_name');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/products', $filename);
            $validated['file_name'] = 'products/' . $filename;
        }

        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        if ($product->file_name) {
            Storage::delete('public/' . $product->file_name);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
