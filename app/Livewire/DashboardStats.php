<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Ingredient;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardStats extends Component
{
    public function render()
    {
        $today = Carbon::today();
        
        $totalSales = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');
            
        $totalTransactions = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();
            
        $criticalStockCount = Ingredient::whereColumn('current_stock', '<=', 'minimum_stock')->count();
        
        $activeShift = Shift::where('status', 'open')->first();

        $topSelling = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereDate('orders.created_at', $today)
            ->where('orders.status', 'completed')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('livewire.dashboard-stats', [
            'totalSales' => $totalSales,
            'totalTransactions' => $totalTransactions,
            'criticalStockCount' => $criticalStockCount,
            'activeShift' => $activeShift,
            'topSelling' => $topSelling
        ]);
    }
}