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

    public function create()
    {
        return view('suppliers.create', ['title' => 'Add New Supplier']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers',
            'contact' => 'required|string|max:50',
            'address' => 'nullable|string'
        ]);

        Supplier::create($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', [
            'supplier' => $supplier,
            'title' => 'Edit Supplier'
        ]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'contact' => 'required|string|max:50',
            'address' => 'nullable|string'
        ]);

        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully');
    }
}