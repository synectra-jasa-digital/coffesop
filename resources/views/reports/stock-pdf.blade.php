<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Stok</title>
    <style>
        body { font-family: sans-serif; color: #1f2937; }
        h1 { color: #398263; font-size: 20px; margin-bottom: 4px; }
        .meta { color: #6b7280; font-size: 12px; margin-bottom: 20px; }
        .cards { display: flex; gap: 16px; margin-bottom: 24px; }
        .card { flex: 1; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px 16px; }
        .card .label { font-size: 12px; color: #6b7280; }
        .card .value { font-size: 22px; font-weight: bold; color: #398263; }
        .card.critical { border-color: #fca5a5; }
        .card.critical .value { color: #dc2626; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th { background: #398263; color: #fff; padding: 8px 10px; text-align: left; }
        td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background: #f9fafb; }
        .badge { padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: bold; }
        .badge.critical { background: #fee2e2; color: #dc2626; }
        .badge.ok { background: #dcfce7; color: #16a34a; }
    </style>
</head>
<body>
    <h1>Good Coffee. - Laporan Stok</h1>
    <div class="meta">Dihasilkan: {{ now()->format('d M Y H:i') }}</div>

    <div class="cards">
        <div class="card">
            <div class="label">Total Jenis Bahan Baku</div>
            <div class="value">{{ number_format($total_items, 0, ',', '.') }}</div>
        </div>
        <div class="card critical">
            <div class="label">Item Kritis (Butuh Restock)</div>
            <div class="value">{{ number_format($critical_items, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Bahan Baku</th>
                <th>Stok Sistem</th>
                <th>Batas Minimum</th>
                <th>Satuan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ingredients as $ing)
                <tr>
                    <td>{{ $ing->name }}</td>
                    <td>{{ number_format($ing->current_stock, 0, ',', '.') }}</td>
                    <td>{{ number_format($ing->minimum_stock, 0, ',', '.') }}</td>
                    <td>{{ $ing->unit }}</td>
                    <td>
                        @if($ing->current_stock <= $ing->minimum_stock)
                            <span class="badge critical">Kritis</span>
                        @else
                            <span class="badge ok">Aman</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center; padding: 20px;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>