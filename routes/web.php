<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\gestionnaire\DashboardController as GestionnaireDashboardController;
use App\Http\Controllers\Magasin\DashboardController as UserDashboardController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\InvoicesController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User Routes
Route::middleware(['auth', 'magasin'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('magasin.dashboard');
});

// Manager Routes
Route::prefix('gestionnaire')->middleware(['auth', 'gestionnaire'])->group(function () {
    Route::get('/dashboard', [GestionnaireDashboardController::class, 'index'])->name('gestionnaire.dashboard');
});
// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('users', UserController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy'
    ]);
});
// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});




// Shared Routes for both Admin and Manager
Route::middleware(['auth', 'role:admin,gestionnaire'])->group(function () {
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Suppliers
    Route::resource('suppliers', SupplierController::class);
    
    // Products
    Route::get('products', [ProductsController::class, 'index']);
    // Purchases
    Route::resource('purchases', PurchaseController::class);
});
// Shared Routes for Admin and Magasin
Route::middleware(['auth', 'role:admin,magasin'])->group(function () {
    Route::resource('sales', SalesController::class);
    Route::resource('products', ProductsController::class);
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportsController::class, 'generate'])->name('reports.generate');
    Route::get('/invoices', [InvoicesController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{sale}', [InvoicesController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{sale}/download', [InvoicesController::class, 'download'])->name('invoices.download');
});




