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
                            ->with('error', 'Stock insuffisant. Disponible: ' . $availableStock);
            }

            // Create the sale
            $sale = Sale::create($validated);

            // Get related models for notification
            $product = Product::find($validated['product_id']);
            $client = Client::find($validated['client_id']);

            // Create detailed notification in French
            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => sprintf(
                    "Nouvelle vente créée (ID: %d)\nClient: %s\nProduit: %s\nQuantité: %d\nPrix: %.2f\nDate: %s",
                    $sale->id,
                    $client->name,
                    $product->name,
                    $validated['quantity'],
                    $validated['price'],
                    $validated['date']
                ),
                'read' => false,
                'type' => 'sale',
                'data' => json_encode([
                    'sale_id' => $sale->id,
                    'action' => 'created',
                    'details' => [
                        'client' => $client->name,
                        'product' => $product->name,
                        'quantity' => $validated['quantity'],
                        'price' => $validated['price'],
                        'date' => $validated['date'],
                        'total_amount' => $validated['quantity'] * $validated['price']
                    ]
                ])
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                            ->with('success', 'Vente créée avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Erreur: ' . $e->getMessage());
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
            // Store original values before update
            $originalValues = [
                'client' => $sale->client->name,
                'product' => $sale->product->name,
                'quantity' => $sale->quantity,
                'price' => $sale->price,
                'date' => $sale->date,
                'total_amount' => $sale->quantity * $sale->price
            ];

            $quantityDiff = $validated['quantity'] - $sale->quantity;

            if ($quantityDiff > 0) { // If increasing quantity
                $totalPurchased = Purchase::where('product_id', $validated['product_id'])
                                        ->sum('quantity');
                $totalSold = Sale::where('product_id', $validated['product_id'])
                                ->sum('quantity');
                $availableStock = $totalPurchased - $totalSold;

                if ($quantityDiff > $availableStock) {
                    return back()->withInput()
                                ->with('error', 'Stock insuffisant pour cette augmentation. Disponible: ' . $availableStock);
                }
            }

            $sale->update($validated);

            // Get new values after update
            $newClient = Client::find($validated['client_id']);
            $newProduct = Product::find($validated['product_id']);

            // Prepare detailed change description in French
            $changeDetails = [];

            if ($originalValues['client'] !== $newClient->name) {
                $changeDetails[] = sprintf(
                    "Client changé de '%s' à '%s'",
                    $originalValues['client'],
                    $newClient->name
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

            // Calculate total amount difference
            $newTotalAmount = $validated['quantity'] * $validated['price'];
            if ($originalValues['total_amount'] != $newTotalAmount) {
                $changeDetails[] = sprintf(
                    "Montant total changé de %.2f à %.2f",
                    $originalValues['total_amount'],
                    $newTotalAmount
                );
            }

            // Create detailed notification in French
            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => "Vente #" . $sale->id . " mise à jour :\n" . implode("\n", $changeDetails),
                'read' => false,
                'type' => 'sale',
                'data' => json_encode([
                    'sale_id' => $sale->id,
                    'action' => 'updated',
                    'changes' => [
                        'client' => [
                            'from' => $originalValues['client'],
                            'to' => $newClient->name
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
                        ],
                        'total_amount' => [
                            'from' => $originalValues['total_amount'],
                            'to' => $newTotalAmount
                        ]
                    ],
                    'updated_at' => now()->toDateTimeString()
                ])
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                            ->with('success', 'Vente mise à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy(Sale $sale)
    {
        DB::beginTransaction();
        try {
            // Store sale details before deletion
            $saleDetails = [
                'id' => $sale->id,
                'client' => $sale->client->name,
                'product' => $sale->product->name,
                'quantity' => $sale->quantity,
                'price' => $sale->price,
                'date' => $sale->date,
                'total_amount' => $sale->quantity * $sale->price
            ];

            $sale->delete();

            // Create detailed deletion notification in French
            Notification::create([
                'user_id' => Auth::id(),
                'action_user_id' => Auth::id(),
                'message' => sprintf(
                    "Vente supprimée (ID: %d)\nClient: %s\nProduit: %s\nQuantité: %d\nPrix: %.2f\nDate: %s\nMontant total: %.2f",
                    $saleDetails['id'],
                    $saleDetails['client'],
                    $saleDetails['product'],
                    $saleDetails['quantity'],
                    $saleDetails['price'],
                    $saleDetails['date'],
                    $saleDetails['total_amount']
                ),
                'read' => false,
                'type' => 'sale',
                'data' => json_encode([
                    'action' => 'deleted',
                    'deleted_sale' => $saleDetails,
                    'deleted_at' => now()->toDateTimeString()
                ])
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                            ->with('success', 'Vente supprimée avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
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