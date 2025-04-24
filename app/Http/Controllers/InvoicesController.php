<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicesController extends Controller
{
    public function index()
    {
        $invoices = Sale::with(['product', 'client'])->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function show(Sale $sale)
    {
        return view('invoices.show', compact('sale'));
    }

    public function download(Sale $sale)
    {
        $pdf = Pdf::loadView('invoices.pdf', compact('sale'));
        return $pdf->download('invoice-'.$sale->id.'.pdf');
    }
}