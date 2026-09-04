<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pesanan #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            color: #000;
            width: 58mm; /* Standard thermal receipt width */
            margin: 0;
            padding: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .border-bottom { border-bottom: 1px dashed #000; padding-bottom: 4px; margin-bottom: 4px; }
        .border-top { border-top: 1px dashed #000; padding-top: 4px; margin-top: 4px; }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        .w-full { width: 100%; }
        .flex { display: flex; justify-content: space-between; }
        
        /* Print styles */
        @media print {
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print();">
    <div class="text-center mb-4">
        <div class="font-bold mb-1" style="font-size: 14px;">{{ $settings->get('store_name', 'Good Coffee.') }}</div>
        <div>{{ $settings->get('store_address', 'Jl. Kopi Nikmat No. 123') }}</div>
        <div>{{ $settings->get('store_phone', '0812-3456-7890') }}</div>
    </div>

    <div class="border-bottom border-top mb-2">
        <div class="flex">
            <span>No: {{ $order->order_number }}</span>
            <span>{{ $order->created_at->format('d/m/y H:i') }}</span>
        </div>
        <div class="flex">
            <span>Kasir: {{ $order->user->name ?? 'Kasir' }}</span>
            <span>Tipe: {{ ucfirst(str_replace('-', ' ', $order->type)) }}</span>
        </div>
        @if($order->table)
            <div>Meja: {{ $order->table->number }}</div>
        @endif
    </div>

    <table class="mb-2 border-bottom">
        @foreach($order->items as $item)
        <tr>
            <td colspan="3">{{ $item->product->name ?? 'Item Dihapus' }}</td>
        </tr>
        <tr>
            <td>{{ $item->quantity }}x</td>
            <td>{{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($item->notes)
        <tr>
            <td colspan="3" style="font-style: italic; padding-left: 10px;">- {{ $item->notes }}</td>
        </tr>
        @endif
        @endforeach
    </table>

    <table class="mb-2 border-bottom">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">{{ number_format($order->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($order->discount_amount > 0)
        <tr>
            <td>Diskon</td>
            <td class="text-right">-{{ number_format($order->discount_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($order->service_charge_amount > 0)
        <tr>
            <td>Service Charge</td>
            <td class="text-right">{{ number_format($order->service_charge_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($order->tax_amount > 0)
        <tr>
            <td>Pajak (PPN)</td>
            <td class="text-right">{{ number_format($order->tax_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr class="font-bold" style="font-size: 14px;">
            <td style="padding-top: 4px;">TOTAL</td>
            <td class="text-right" style="padding-top: 4px;">{{ number_format($order->total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table class="mb-4 border-bottom">
        @foreach($order->payments as $payment)
        <tr>
            <td>
                @switch($payment->payment_method)
                    @case('cash') Tunai @break
                    @case('qris') QRIS @break
                    @case('ewallet') E-Wallet @break
                    @case('bank_transfer') Transfer Bank @break
                    @case('card') Kartu @break
                    @default {{ ucfirst($payment->payment_method) }}
                @endswitch
            </td>
            <td class="text-right">{{ number_format($payment->amount, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        
        @if($order->payments->where('payment_method', 'cash')->sum('amount') > $order->total)
            @php
                $cashPaid = $order->payments->where('payment_method', 'cash')->sum('amount');
                $change = $cashPaid - $order->total;
            @endphp
            <tr>
                <td>Kembalian</td>
                <td class="text-right">{{ number_format($change, 0, ',', '.') }}</td>
            </tr>
        @endif
    </table>

    <div class="text-center mb-4">
        <div>Terima kasih atas kunjungan Anda!</div>
        <div style="font-style: italic;">Layanan kritik & saran: {{ $settings->get('store_phone', '0812-3456-7890') }}</div>
    </div>
</body>
</html>
