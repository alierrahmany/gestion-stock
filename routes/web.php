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
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\ClientController;

// Redirect to login
Route::get('/', fn() => redirect()->route('login'));

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

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportsController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/export/{format}', [ReportsController::class, 'export'])->name('reports.export');
});



// Magasin Routes
Route::middleware(['auth', 'magasin'])->group(function () {
    Route::get('/dashboard', [MagasinDashboardController::class, 'index'])->name('magasin.dashboard');
});

// Gestionnaire Routes
Route::prefix('gestionnaire')->middleware(['auth', 'gestionnaire'])->group(function () {
    Route::get('/dashboard', [GestionnaireDashboardController::class, 'index'])->name('gestionnaire.dashboard');
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

    // Notification Routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationsController::class, 'index'])->name('admin.notifications.index');
        Route::post('/{notification}/mark-as-read', [NotificationsController::class, 'markAsRead'])->name('admin.notifications.mark-as-read');
        Route::post('/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-as-read');
        Route::delete('/{notification}', [NotificationsController::class, 'destroy'])->name('admin.notifications.destroy');
    });
});

// Shared Routes for Admin & Gestionnaire
Route::middleware(['auth', 'role:admin,gestionnaire'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductsController::class)->names([

        'create'  => 'products.create',
        'store'   => 'products.store',
        'edit'    => 'products.edit',
        'update'  => 'products.update',
        'destroy' => 'products.destroy',
    ]);
    Route::resource('purchases', PurchaseController::class)->except(['show']);

    // Purchase Documents accessible to both admin and gestionnaire
    Route::prefix('documents')->group(function () {
        Route::get('/purchases', [DocumentsController::class, 'purchases'])->name('documents.purchases');
        Route::get('/purchases/{purchase}/download', [DocumentsController::class, 'downloadPurchaseOrder'])
            ->name('documents.purchase-order.download');
        Route::get('/purchases/print-all', [DocumentsController::class, 'printAllPurchases'])
            ->name('documents.purchases.print-all');
    });
});

// Shared Routes for Admin & Magasin
Route::middleware(['auth', 'role:admin,magasin'])->group(function () {
    Route::resource('sales', SalesController::class)->except(['show']);
    Route::get('sales/stock/{product}', [SalesController::class, 'getAvailableStock']);
    Route::get('sales/report', [SalesController::class, 'report'])->name('sales.report');



    // Delivery Documents accessible to both admin and magasin
    Route::prefix('documents')->group(function () {
        Route::get('/sales', [DocumentsController::class, 'sales'])->name('documents.sales');
        Route::get('/sales/{sale}/download', [DocumentsController::class, 'downloadDeliveryNote'])
            ->name('documents.delivery-note.download');
        Route::get('/sales/print-all', [DocumentsController::class, 'printAllSales'])
            ->name('documents.sales.print-all');
    });

    Route::resource('clients', ClientController::class)->middleware('auth');
});

// Documents index accessible to all authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/documents', [DocumentsController::class, 'index'])->name('documents.index');
});

Route::middleware(['auth', 'role:admin,gestionnaire,magasin'])->group(function () {
    Route::resource('products', ProductsController::class)->names([
        'index'   => 'products.index'
    ]);
});
