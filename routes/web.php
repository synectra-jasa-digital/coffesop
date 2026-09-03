<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pos\Terminal;
use App\Livewire\Kds\Display;
use App\Livewire\Admin\Products\Index as AdminProductsIndex;
use App\Livewire\Admin\Inventory\Index as AdminInventoryIndex;
use App\Livewire\Admin\Reports\Index as AdminReportsIndex;
use App\Livewire\Admin\Suppliers\Index as AdminSuppliersIndex;
use App\Livewire\Admin\Discounts\Index as AdminDiscountsIndex;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    // POS - Kasir, Manager, Owner
    Route::middleware(['role:Kasir|Manager/Supervisor|Owner/Admin'])->group(function () {
        Route::get('/pos', Terminal::class)->name('pos.index');
        Route::get('/pos/history', \App\Livewire\Pos\History::class)->name('pos.history');
    });

    // KDS - Barista, Manager, Owner
    Route::middleware(['role:Barista/Gudang|Manager/Supervisor|Owner/Admin'])->group(function () {
        Route::get('/kds', Display::class)->name('kds.index');
    });
    
    // Inventory - Barista, Manager, Owner
    Route::middleware(['role:Barista/Gudang|Manager/Supervisor|Owner/Admin'])->group(function () {
        Route::get('/admin/inventory', AdminInventoryIndex::class)->name('admin.inventory.index');
    });

    // Products - Manager, Owner
    Route::middleware(['role:Manager/Supervisor|Owner/Admin'])->group(function () {
        Route::get('/admin/products', AdminProductsIndex::class)->name('admin.products.index');
        Route::get('/admin/products/{product}/recipe', \App\Livewire\Admin\Products\RecipeManager::class)->name('admin.products.recipe');
        Route::get('/admin/master', \App\Livewire\Admin\Master\Index::class)->name('admin.master.index');
    });

    // Suppliers - Manager, Owner
    Route::middleware(['role:Manager/Supervisor|Owner/Admin'])->group(function () {
        Route::get('/admin/suppliers', AdminSuppliersIndex::class)->name('admin.suppliers.index');
    });

    // Discounts / Promo - Manager, Owner
    Route::middleware(['role:Manager/Supervisor|Owner/Admin'])->group(function () {
        Route::get('/admin/discounts', AdminDiscountsIndex::class)->name('admin.discounts.index');
    });

    // Reports - Manager, Owner
    Route::middleware(['role:Manager/Supervisor|Owner/Admin'])->group(function () {
        Route::get('/admin/reports', AdminReportsIndex::class)->name('admin.reports.index');
    });

    // Settings & Users - Owner
    Route::middleware(['role:Owner/Admin'])->group(function () {
        Route::get('/admin/settings', \App\Livewire\Admin\Settings\Index::class)->name('admin.settings.index');
        Route::get('/admin/users', \App\Livewire\Admin\Users\Index::class)->name('admin.users.index');
    });
});

require __DIR__.'/auth.php';
