<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\gestionnaire\DashboardController as GestionnaireDashboardController;
use App\Http\Controllers\Magasin\DashboardController as MagasinDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\InvoicesController;

// Redirect to login
Route::get('/', fn () => redirect()->route('login'));

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/users/{user}/update-image', [UserController::class, 'updateImage'])->name('users.update-image');
});

// Notification Routes
Route::post('/notifications/{notification}/mark-as-read', function ($id) {
    auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
    return back();
})->name('notifications.markAsRead');

Route::get('/test-notification', function () {
    $product = \App\Models\Product::first();
    $product->quantity = 1;
    $product->save();
    return "Test notification sent!";
});

// Magasin Routes
Route::middleware(['auth', 'magasin'])->group(function () {
    Route::get('/dashboard', [MagasinDashboardController::class, 'index'])->name('magasin.dashboard');
});

// Gestionnaire Routes
Route::prefix('gestionnaire')->middleware(['auth', 'gestionnaire'])->group(function () {
    Route::get('/dashboard', [GestionnaireDashboardController::class, 'index'])->name('gestionnaire.dashboard');
    Route::resource('products', ProductsController::class)->names([
        'index'   => 'gestionnaire.products.index',
        'create'  => 'gestionnaire.products.create',
        'store'   => 'gestionnaire.products.store',
        'edit'    => 'gestionnaire.products.edit',
        'update'  => 'gestionnaire.products.update',
        'destroy' => 'gestionnaire.products.destroy',
    ]);
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('users', UserController::class)->names([
        'index'   => 'users.index',
        'create'  => 'users.create',
        'store'   => 'users.store',
        'edit'    => 'users.edit',
        'update'  => 'users.update',
        'destroy' => 'users.destroy',
    ]);
});

// Shared Routes for Admin & Gestionnaire
Route::middleware(['auth', 'role:admin,gestionnaire'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductsController::class)->names([
        'index'   => 'products.index',
        'create'  => 'products.create',
        'store'   => 'products.store',
        'edit'    => 'products.edit',
        'update'  => 'products.update',
        'destroy' => 'products.destroy',
    ]);
    Route::resource('purchases', PurchaseController::class)->except(['show']);
});

// Shared Routes for Admin & Magasin
Route::middleware(['auth', 'role:admin,magasin'])->group(function () {
    Route::resource('sales', SalesController::class)->except(['show']);
    Route::get('sales/stock/{product}', [SalesController::class, 'getAvailableStock']);
    Route::get('sales/report', [SalesController::class, 'report'])->name('sales.report');

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportsController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/export/{format}', [ReportsController::class, 'export'])->name('reports.export');

    Route::get('/invoices', [InvoicesController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{sale}', [InvoicesController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{sale}/download', [InvoicesController::class, 'download'])->name('invoices.download');
});
