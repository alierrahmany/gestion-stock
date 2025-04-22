<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $userCounts = [
            'admin' => User::where('role', 'admin')->count(),
            'gestionnaire' => User::where('role', 'gestionnaire')->count(),
            'magasin' => User::where('role', 'magasin')->count()
        ];

        return view('admin.dashboard', compact('userCounts'));
    }
}