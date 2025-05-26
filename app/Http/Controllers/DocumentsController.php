<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function index()
    {
        return view('documents.index');
    }

    public function sales(Request $request)
    {
        $query = Sale::with(['product', 'client'])->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $sales = $query->paginate(10);

        return view('documents.sales', [
            'sales' => $sales,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ]);
    }


    public function purchases(Request $request)
    {
        $query = Purchase::with(['supplier', 'product']);

        // Apply date range filters if provided
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->input('date_to'));
        }

        // Paginate the results
        $purchases = $query->orderBy('date', 'desc')->paginate(10);

        return view('documents.purchases', compact('purchases'));
    }

    public function downloadDeliveryNote(Sale $sale)
    {
        $pdf = Pdf::loadView('documents.pdf.delivery-note', compact('sale'));
        return $pdf->download('delivery-note-' . $sale->id . '.pdf');
    }

    public function downloadPurchaseOrder(Purchase $purchase)
    {
        $pdf = Pdf::loadView('documents.pdf.purchase-order', compact('purchase'));
        return $pdf->download('purchase-order-' . $purchase->id . '.pdf');
    }

    public function printAllSales(Request $request)
    {
        $query = Sale::with(['product', 'client']);

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $sales = $query->latest()->get();

        $pdf = Pdf::loadView('documents.pdf.all-sales', [
            'sales' => $sales,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to
        ]);

        return $pdf->download('Bons-de-Livraison.pdf' . now()->format('Y-m-d') . '.pdf');
    }

    public function printAllPurchases(Request $request)
    {
        $query = Purchase::with(['supplier', 'product']);

        // Apply the same filters as the index page
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }
        if ($request->filled('product')) {
            $query->where('product_id', $request->product);
        }

        $purchases = $query->orderBy('date', 'desc')->get();

        $pdf = PDF::loadView('documents.pdf.all-purchases', [
            'purchases' => $purchases,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ]);

        return $pdf->download('bons-achats-' . now()->format('Y-m-d') . '.pdf');
    }
}
