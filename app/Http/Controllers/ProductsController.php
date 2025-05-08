<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
        $categories = Category::all();

        if ($request->has('category') && $request->category !== '') {
            $query->where('categorie_id', $request->category);
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $products = $query->latest()->paginate(10);
        return view('products.index', compact('products', 'categories'));
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
            'categorie_id' => 'required|exists:categories,id',
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

        $product = Product::create($validated);

        // Create notification
        // In store/update/destroy methods
        Notification::create([
            'user_id' => Auth::id(),
            'action_user_id' => Auth::id(),
            'message' => 'Product ' . $product->name . ' was added',
            'read' => false,
            'type' => 'product'
        ]);

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
            'categorie_id' => 'required|exists:categories,id',
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

        // Create notification
        // In store/update/destroy methods
        Notification::create([
            'user_id' => Auth::id(),
            'action_user_id' => Auth::id(),
            'message' => 'Product ' . $product->name . ' was updated',
            'read' => false,
            'type' => 'product'
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        if ($product->file_name) {
            Storage::delete('public/' . $product->file_name);
        }
        $product->delete();
        $productName = $product->name;

        // Create notification
        Notification::create([
            'user_id' => Auth::id(),
            'action_user_id' => Auth::id(),
            'message' => 'Product ' . $product->name . ' was Deleted',
            'read' => false,
            'type' => 'product'
        ]);

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
