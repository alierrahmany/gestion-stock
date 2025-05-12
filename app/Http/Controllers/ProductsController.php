<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Ensure directory exists
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }

            $imageName = 'no_image.jpg';
            if ($request->hasFile('image')) {
                $imageName = time().'_'.Str::slug(pathinfo($request->image->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$request->image->extension();
                $request->image->storeAs('products', $imageName, 'public');
            }

            $product = Product::create([
                'name' => $request->name,
                'categorie_id' => $request->categorie_id,
                'date' => now(),
                'file_name' => $imageName
            ]);

            // Create notification
            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => 'Product ' . $product->name . ' was added',
                'read' => false,
                'type' => 'product'
            ]);

            return redirect()->route('products.index')->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            Log::error('Product creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error creating product. Please try again.']);
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'categorie_id' => $request->categorie_id
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists and not default
            if ($product->file_name && $product->file_name !== 'no_image.jpg') {
                Storage::disk('public')->delete('products/' . $product->file_name);
            }

            // Store new image
            $imageName = time().'_'.Str::slug(pathinfo($request->image->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$request->image->extension();
            $request->image->storeAs('products', $imageName, 'public');
            $data['file_name'] = $imageName;
        }

        $product->update($data);

        // Create notification
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
        if ($product->file_name && $product->file_name !== 'no_image.jpg') {
            Storage::disk('public')->delete('product_images/' . $product->file_name);
        }

        $productName = $product->name;
        $product->delete();

        // Create notification
        Notification::create([
            'user_id' => Auth::id(),
            'action_user_id' => Auth::id(),
            'message' => 'Product ' . $productName . ' was deleted',
            'read' => false,
            'type' => 'product'
        ]);

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
