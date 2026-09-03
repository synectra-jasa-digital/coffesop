<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan - {{ $date }}</title>
    <style>
        body { font-family: sans-serif; color: #1f2937; }
        h1 { color: #398263; font-size: 20px; margin-bottom: 4px; }
        .meta { color: #6b7280; font-size: 12px; margin-bottom: 20px; }
        .cards { display: flex; gap: 16px; margin-bottom: 24px; }
        .card { flex: 1; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px 16px; }
        .card .label { font-size: 12px; color: #6b7280; }
        .card .value { font-size: 22px; font-weight: bold; color: #398263; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th { background: #398263; color: #fff; padding: 8px 10px; text-align: left; }
        td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background: #f9fafb; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Good Coffee. - Laporan Penjualan</h1>
    <div class="meta">Tanggal: {{ $date }} - Dihasilkan: {{ now()->format('d M Y H:i') }}</div>

    <div class="cards">
        <div class="card">
            <div class="label">Total Pendapatan</div>
            <div class="value">Rp {{ number_format($total_revenue, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div class="label">Total Transaksi</div>
            <div class="value">{{ number_format($total_transactions, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div class="label">Rata-rata Transaksi</div>
            <div class="value">Rp {{ number_format($avg_transaction, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No. Order</th>
                <th>Kasir</th>
                <th>Meja</th>
                <th>Tipe</th>
                <th>Subtotal</th>
                <th>Pajak</th>
                <th>Diskon</th>
                <th>Total</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row[0] }}</td>
                    <td>{{ $row[1] }}</td>
                    <td>{{ $row[2] }}</td>
                    <td>{{ $row[3] }}</td>
                    <td>Rp {{ number_format($row[4], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row[5], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row[6], 0, ',', '.') }}</td>
                    <td class="total">Rp {{ number_format($row[7], 0, ',', '.') }}</td>
                    <td>{{ $row[9] }}</td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center; padding: 20px;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>