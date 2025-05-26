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
            $query->whereDate('date', $request->date); 
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

            // Get related models for notification
            $supplier = Supplier::find($validated['supplier_id']);
            $product = Product::find($validated['product_id']);

            // Create detailed notification in French
            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => sprintf(
                    "Nouvel achat créé (ID: %d)\nFournisseur: %s\nProduit: %s\nQuantité: %d\nPrix: %.2f\nDate: %s",
                    $purchase->id,
                    $supplier->name,
                    $product->name,
                    $validated['quantity'],
                    $validated['price'],
                    $validated['date']
                ),
                'read' => false,
                'type' => 'purchase',
                'data' => json_encode([
                    'purchase_id' => $purchase->id,
                    'action' => 'created',
                    'details' => [
                        'supplier' => $supplier->name,
                        'product' => $product->name,
                        'quantity' => $validated['quantity'],
                        'price' => $validated['price'],
                        'date' => $validated['date']
                    ]
                ])
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
            'price' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Store original values before update
            $originalValues = [
                'supplier' => $purchase->supplier->name,
                'product' => $purchase->product->name,
                'quantity' => $purchase->quantity,
                'price' => $purchase->price,
                'date' => $purchase->date
            ];

            $quantityDiff = $validated['quantity'] - $purchase->quantity;

            // Update with correct field names
            $purchase->update([
                'supplier_id' => $validated['supplier_id'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'date' => $validated['date'],
            ]);

            // Get new values after update
            $newSupplier = Supplier::find($validated['supplier_id']);
            $newProduct = Product::find($validated['product_id']);

            // Prepare detailed change description in French
            $changeDetails = [];

            if ($originalValues['supplier'] !== $newSupplier->name) {
                $changeDetails[] = sprintf(
                    "Fournisseur changé de '%s' à '%s'",
                    $originalValues['supplier'],
                    $newSupplier->name
                );
            }

            if ($originalValues['product'] !== $newProduct->name) {
                $changeDetails[] = sprintf(
                    "Produit changé de '%s' à '%s'",
                    $originalValues['product'],
                    $newProduct->name
                );
            }

            if ($originalValues['quantity'] != $validated['quantity']) {
                $changeDetails[] = sprintf(
                    "Quantité changée de %d à %d (différence: %+d)",
                    $originalValues['quantity'],
                    $validated['quantity'],
                    $quantityDiff
                );
            }

            if ($originalValues['price'] != $validated['price']) {
                $changeDetails[] = sprintf(
                    "Prix changé de %.2f à %.2f",
                    $originalValues['price'],
                    $validated['price']
                );
            }

            if ($originalValues['date'] != $validated['date']) {
                $changeDetails[] = sprintf(
                    "Date changée de %s à %s",
                    $originalValues['date'],
                    $validated['date']
                );
            }

            // Create detailed notification in French
            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => "Achat #" . $purchase->id . " mis à jour :\n" . implode("\n", $changeDetails),
                'read' => false,
                'type' => 'purchase',
                'data' => json_encode([
                    'purchase_id' => $purchase->id,
                    'action' => 'updated',
                    'changes' => [
                        'supplier' => [
                            'from' => $originalValues['supplier'],
                            'to' => $newSupplier->name
                        ],
                        'product' => [
                            'from' => $originalValues['product'],
                            'to' => $newProduct->name
                        ],
                        'quantity' => [
                            'from' => $originalValues['quantity'],
                            'to' => $validated['quantity'],
                            'difference' => $quantityDiff
                        ],
                        'price' => [
                            'from' => $originalValues['price'],
                            'to' => $validated['price']
                        ],
                        'date' => [
                            'from' => $originalValues['date'],
                            'to' => $validated['date']
                        ]
                    ],
                    'updated_at' => now()->toDateTimeString()
                ])
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
            // Store purchase details before deletion
            $purchaseDetails = [
                'id' => $purchase->id,
                'supplier' => $purchase->supplier->name,
                'product' => $purchase->product->name,
                'quantity' => $purchase->quantity,
                'price' => $purchase->price,
                'date' => $purchase->date
            ];

            $purchase->delete();

            // Create detailed deletion notification in French
            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => sprintf(
                    "Achat supprimé (ID: %d)\nFournisseur: %s\nProduit: %s\nQuantité: %d\nPrix: %.2f\nDate: %s",
                    $purchaseDetails['id'],
                    $purchaseDetails['supplier'],
                    $purchaseDetails['product'],
                    $purchaseDetails['quantity'],
                    $purchaseDetails['price'],
                    $purchaseDetails['date']
                ),
                'read' => false,
                'type' => 'purchase',
                'data' => json_encode([
                    'action' => 'deleted',
                    'deleted_purchase' => $purchaseDetails,
                    'deleted_at' => now()->toDateTimeString()
                ])
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