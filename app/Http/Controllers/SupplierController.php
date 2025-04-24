<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        $title = 'Liste des Fournisseurs';
        return view('suppliers.index', compact('suppliers', 'title'));
    }

    public function show(Supplier $supplier)
    {
        $title = 'Détails du Fournisseur';
        return view('suppliers.show', compact('supplier', 'title'));
    }
}