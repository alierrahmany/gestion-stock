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

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    });
            });
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->where('categorie_id', $request->category);
        }

        $products = $query->latest()->paginate(10)->appends(request()->query());

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
                $imageName = time() . '_' . Str::slug(pathinfo($request->image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $request->image->extension();
                $request->image->storeAs('products', $imageName, 'public');
            }

            $product = Product::create([
                'name' => $request->name,
                'categorie_id' => $request->categorie_id,
                'date' => now(),
                'file_name' => $imageName
            ]);

            // Create notification
            // Get category name for notification
            $category = Category::find($request->categorie_id);

            // Create detailed notification in French
            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => sprintf(
                    "Nouveau produit créé : %s (Catégorie : %s)",
                    $product->name,
                    $category->name
                ),
                'read' => false,
                'type' => 'product',
                'data' => json_encode([
                    'product_id' => $product->id,
                    'action' => 'created',
                    'details' => [
                        'name' => $product->name,
                        'category' => $category->name,
                        'image' => $imageName !== 'no_image.jpg' ? 'Uploaded' : 'Default'
                    ]
                ])
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

        // Store original values before any changes
        $originalValues = [
            'name' => $product->name,
            'category' => $product->category->name,
            'image' => $product->file_name
        ];

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
            $imageName = time() . '_' . Str::slug(pathinfo($request->image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $request->image->extension();
            $request->image->storeAs('products', $imageName, 'public');
            $data['file_name'] = $imageName;
        }

        $product->update($data);

        // Get new category name
        $newCategory = Category::find($request->categorie_id);

        // Prepare detailed change description in French
        $changeDetails = [];

        if ($originalValues['name'] !== $request->name) {
            $changeDetails[] = sprintf(
                "Nom changé de '%s' à '%s'",
                $originalValues['name'],
                $request->name
            );
        }

        if ($originalValues['category'] !== $newCategory->name) {
            $changeDetails[] = sprintf(
                "Catégorie changée de '%s' à '%s'",
                $originalValues['category'],
                $newCategory->name
            );
        }

        if (isset($data['file_name']) && $originalValues['image'] !== $data['file_name']) {
            $changeDetails[] = "L'image du produit a été mise à jour";
        }

        // Only create notification if there were actual changes
        if (!empty($changeDetails)) {
            $notificationMessage = "Produit '{$product->name}' mis à jour :\n" . implode("\n", $changeDetails);

            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => $notificationMessage,
                'read' => false,
                'type' => 'product',
                'data' => json_encode([
                    'product_id' => $product->id,
                    'action' => 'updated',
                    'changes' => [
                        'name' => [
                            'from' => $originalValues['name'],
                            'to' => $request->name
                        ],
                        'category' => [
                            'from' => $originalValues['category'],
                            'to' => $newCategory->name
                        ],
                        'image' => [
                            'from' => $originalValues['image'],
                            'to' => $data['file_name'] ?? $originalValues['image']
                        ]
                    ],
                    'updated_at' => now()->toDateTimeString()
                ])
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        if ($product->file_name && $product->file_name !== 'no_image.jpg') {
            Storage::disk('public')->delete('product_images/' . $product->file_name);
        }

        $productName = $product->name;
        $categoryName = $product->category->name;
        $product->delete();

        // Create detailed deletion notification in French
        Notification::create([
            'user_id' => Auth::id(),
            'action_user_id' => Auth::id(),
            'message' => sprintf(
                "Produit supprimé : %s (Catégorie : %s)",
                $productName,
                $categoryName
            ),
            'read' => false,
            'type' => 'product',
            'data' => json_encode([
                'action' => 'deleted',
                'deleted_product' => [
                    'name' => $productName,
                    'category' => $categoryName,
                    'deleted_at' => now()->toDateTimeString()
                ]
            ])
        ]);

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}