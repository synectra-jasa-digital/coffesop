<?php

namespace App\Reports;

use App\Models\Order;
use App\Models\Ingredient;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use App\Exports\SalesReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReportExportService
{
    public function salesDailyExcel(Carbon $date)
    {
        $rows = $this->buildSalesRows(
            Order::whereDate('created_at', $date)->where('status', 'completed')->get()
        );

        return app(Excel::class)->download(
            new SalesReportExport($rows),
            'laporan_penjualan_' . $date->format('Y-m-d') . '.xlsx'
        );
    }

    public function salesDailyPdf(Carbon $date)
    {
        $orders = Order::whereDate('created_at', $date)->where('status', 'completed')->get();

        $data = [
            'date' => $date->format('d M Y'),
            'total_revenue' => $orders->sum('total'),
            'total_transactions' => $orders->count(),
            'avg_transaction' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
            'rows' => $this->buildSalesRows($orders),
        ];

        $pdf = Pdf::loadView('reports.sales-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5', true)
            ->setOption('isRemoteEnabled', true);

        return new Response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan_penjualan_' . $date->format('Y-m-d') . '.pdf"',
        ]);
    }

    public function stockPdf()
    {
        $ingredients = Ingredient::all();

        $data = [
            'total_items' => $ingredients->count(),
            'critical_items' => $ingredients->filter(fn ($i) => $i->current_stock <= $i->minimum_stock)->count(),
            'ingredients' => $ingredients,
        ];

        $pdf = Pdf::loadView('reports.stock-pdf', $data)
            ->setPaper('a4', 'portrait');

        return new Response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan_stok.pdf"',
        ]);
    }

    protected function buildSalesRows($orders)
    {
        return $orders->map(function ($order) {
            return [
                $order->order_number,
                $order->user->name ?? '-',
                $order->table?->number ?? '-',
                $order->type,
                (float) $order->subtotal,
                (float) $order->tax_amount,
                (float) $order->discount_amount,
                (float) $order->total,
                $order->status,
                $order->created_at->format('d M Y H:i'),
            ];
        });
    }
}