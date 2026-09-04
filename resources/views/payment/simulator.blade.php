<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simulasi Pembayaran - {{ config('app.name', 'Good Coffee') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans p-4">
    <div class="bg-white rounded-sm shadow-lg w-full max-w-md p-8 text-center">
        <div class="w-14 h-14 bg-[#398263] text-white rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-7"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" /></svg>
        </div>
        <h1 class="font-serif font-bold text-2xl text-gray-900 mb-1">Simulasi Pembayaran</h1>
        <p class="text-sm text-gray-500 mb-6">Ini hanya simulasi untuk development. Tidak ada transaksi uang sungguhan.</p>

        <div class="bg-gray-50 rounded-sm p-4 mb-6 text-left space-y-1">
            <div class="flex justify-between text-sm"><span class="text-gray-500">No. Order</span><span class="font-mono font-medium">{{ $order->order_number }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-500">Metode</span><span class="font-medium uppercase">{{ $method }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-500">Total Tagihan</span><span class="font-bold text-[#398263]">Rp {{ number_format($amount, 0, ',', '.') }}</span></div>
        </div>

        <form action="{{ route('payment.webhook', $order->id) }}" method="POST">
            @csrf
            <input type="hidden" name="gateway_id" value="{{ $gatewayId }}">
            <input type="hidden" name="status" value="success">
            <input type="hidden" name="method" value="{{ $method }}">
            <button type="submit" class="w-full bg-[#398263] hover:bg-[#2C6B4F] text-white font-bold py-3 rounded-sm transition-colors">
                Bayar Sekarang (Simulasi)
            </button>
        </form>

        <p class="text-xs text-gray-400 mt-4">Gateway ID: <span class="font-mono">{{ $gatewayId }}</span></p>
    </div>
</body>
</html>