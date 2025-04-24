<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        $title = 'Liste des Catégories';
        return view('categories.index', compact('categories', 'title'));
    }

    public function show(Category $category)
    {
        $title = 'Détails de la Catégorie';
        return view('categories.show', compact('category', 'title'));
    }
}