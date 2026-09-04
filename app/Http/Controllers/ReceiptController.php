<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function show(Order $order)
    {
        // Pastikan relasi diload
        $order->load(['items.product', 'user', 'table', 'payments']);
        
        $settings = Setting::whereIn('key', ['store_name', 'store_address', 'store_phone'])
            ->pluck('value', 'key');
            
        return view('pos.receipt', compact('order', 'settings'));
    }
}
