<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        return view('categories.index', [
            'title' => 'Catégories',
            'categories' => $categories
        ]);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie créée avec succès');
    }

    public function show(Category $category)
    {
        $title = 'Détails de la Catégorie';
        return view('categories.show', compact('category', 'title'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie mise à jour avec succès');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès');
    }
}