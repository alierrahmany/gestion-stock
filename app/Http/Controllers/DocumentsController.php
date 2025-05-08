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

    public function sales()
    {
        $sales = Sale::with(['product', 'client'])->latest()->paginate(10);
        return view('documents.sales', compact('sales'));
    }

    public function purchases()
    {
        $purchases = Purchase::with(['product', 'supplier'])->latest()->paginate(10);
        return view('documents.purchases', compact('purchases'));
    }

    public function downloadDeliveryNote(Sale $sale)
    {
        $pdf = Pdf::loadView('documents.pdf.delivery-note', compact('sale'));
        return $pdf->download('delivery-note-'.$sale->id.'.pdf');
    }

    public function downloadPurchaseOrder(Purchase $purchase)
    {
        $pdf = Pdf::loadView('documents.pdf.purchase-order', compact('purchase'));
        return $pdf->download('purchase-order-'.$purchase->id.'.pdf');
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
    
        return $pdf->download('all-delivery-notes-'.now()->format('Y-m-d').'.pdf');
    }
    
    public function printAllPurchases(Request $request)
    {
        $query = Purchase::with(['product', 'supplier']);
    
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
    
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
    
        $purchases = $query->latest()->get();
    
        $pdf = Pdf::loadView('documents.pdf.all-purchases', [
            'purchases' => $purchases,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to
        ]);
    
        return $pdf->download('all-purchase-orders-'.now()->format('Y-m-d').'.pdf');
    }

}