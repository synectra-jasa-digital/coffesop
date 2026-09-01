<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pos\Terminal;
use App\Livewire\Kds\Display;
use App\Livewire\Admin\Products\Index as AdminProductsIndex;
use App\Livewire\Admin\Inventory\Index as AdminInventoryIndex;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::get('/pos', Terminal::class)->name('pos.index');
    Route::get('/kds', Display::class)->name('kds.index');
    
    Route::get('/admin/products', AdminProductsIndex::class)->name('admin.products.index');
    Route::get('/admin/inventory', AdminInventoryIndex::class)->name('admin.inventory.index');
});

require __DIR__.'/auth.php';
