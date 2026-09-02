<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Order;
use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $reportType = 'sales_daily';
    public $dateFilter;
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->dateFilter = Carbon::today()->format('Y-m-d');
        $this->startDate = Carbon::today()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        $salesData = [];
        $stockData = [];
        $topProducts = [];

        if ($this->reportType === 'sales_daily') {
            $date = Carbon::parse($this->dateFilter);
            
            $orders = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->get();
                
            $salesData = [
                'total_revenue' => $orders->sum('total'),
                'total_transactions' => $orders->count(),
                'avg_transaction' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
            ];
            
            $topProducts = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereDate('orders.created_at', $date)
                ->where('orders.status', 'completed')
                ->select('products.name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_qty')
                ->limit(10)
                ->get();
                
        } elseif ($this->reportType === 'sales_period') {
            $start = Carbon::parse($this->startDate)->startOfDay();
            $end = Carbon::parse($this->endDate)->endOfDay();
            
            $orders = Order::whereBetween('created_at', [$start, $end])
                ->where('status', 'completed')
                ->get();
                
            $salesData = [
                'total_revenue' => $orders->sum('total'),
                'total_transactions' => $orders->count(),
                'avg_transaction' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
            ];

            $topProducts = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$start, $end])
                ->where('orders.status', 'completed')
                ->select('products.name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_qty')
                ->limit(10)
                ->get();

        } elseif ($this->reportType === 'stock') {
            $ingredients = Ingredient::all();
            
            $stockData = [
                'total_items' => $ingredients->count(),
                'critical_items' => $ingredients->filter(function($item) {
                    return $item->current_stock <= $item->minimum_stock;
                })->count(),
                'ingredients' => $ingredients
            ];
        }

        return view('livewire.admin.reports.index', [
            'salesData' => $salesData,
            'stockData' => $stockData,
            'topProducts' => $topProducts
        ]);
    }
}