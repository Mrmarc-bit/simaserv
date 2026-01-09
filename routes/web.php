<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/queue', [PublicController::class, 'store'])->name('queue.store');
Route::get('/ticket/{ticket_code}', [PublicController::class, 'show'])->name('public.ticket.show');
Route::get('/bantuan', [PublicController::class, 'help'])->name('public.help');

// Admin Auth
Route::get('/admin', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Protected Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Services Management
    Route::get('/services', [AdminController::class, 'services'])->name('services.index');
    Route::get('/services/{id}', [AdminController::class, 'show'])->name('services.show');
    Route::get('/services/{id}/invoice', [AdminController::class, 'invoice'])->name('services.invoice');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
    Route::get('/reports/export', [AdminController::class, 'exportReports'])->name('reports.export');
    
    // Actions
    Route::put('/services/{id}/status', [AdminController::class, 'updateStatus'])->name('services.updateStatus');
    Route::put('/services/{id}/payment', [AdminController::class, 'updatePayment'])->name('services.updatePayment');
    Route::post('/services/{id}/items', [AdminController::class, 'addItem'])->name('services.addItem');
    Route::delete('/items/{itemId}', [AdminController::class, 'deleteItem'])->name('items.delete');
    // Help
    Route::get('/help', [AdminController::class, 'help'])->name('help');
});
