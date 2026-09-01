# Product Requirements Document (PRD)
## Aplikasi Terpadu Point of Sale, Stok, dan Laporan Penjualan untuk Coffee Shop

| Item | Detail |
|---|---|
| Nama Produk | Sistem POS Terpadu Coffee Shop |
| Versi Dokumen | 1.0 |
| Tanggal | 1 September 2026 |
| Status | Draft untuk review |
| Skala Bisnis | Single outlet (1 lokasi) |
| Stack Teknis | Laravel + Livewire + Tailwind CSS + MySQL |

---

## 1. Ringkasan Eksekutif

Dokumen ini mendefinisikan kebutuhan untuk aplikasi berbasis website yang mengelola operasional coffee shop dalam satu sistem. Aplikasi mencakup empat pilar utama: transaksi penjualan (POS), manajemen stok bahan baku berbasis resep, dapur dan order digital, serta laporan penjualan.

Aplikasi dibangun dengan Laravel dan Livewire agar antarmuka terasa cepat dan interaktif tanpa reload halaman, dengan Tailwind CSS untuk tampilan yang bisa disesuaikan dengan branding coffee shop. Database menggunakan MySQL.

## 2. Latar Belakang dan Masalah

Coffee shop butuh satu sistem yang menyatukan kasir, stok, dan laporan. Tanpa sistem terpadu, muncul masalah berikut:

- Kasir mencatat transaksi manual atau di aplikasi terpisah dari stok, sehingga stok bahan baku tidak update otomatis.
- Owner tidak tahu bahan baku mana yang menipis sampai kehabisan saat jam sibuk.
- Laporan penjualan harian dan bulanan dibuat manual, rawan salah hitung.
- Barista menerima order lewat kertas atau teriakan dari kasir, order gampang tertukar atau terlewat.
- Owner tidak punya data menu terlaris dan margin keuntungan per menu secara real-time.

## 3. Tujuan Produk

1. Menyatukan proses kasir, stok, dan laporan dalam satu aplikasi berbasis web.
2. Mengurangi selisih stok dengan pengurangan otomatis berdasarkan resep tiap menu.
3. Mempercepat alur order dari kasir ke dapur lewat kitchen display digital.
4. Memberi owner data penjualan dan stok secara real-time, kapan saja bisa diakses lewat browser.
5. Menyediakan opsi pembayaran digital langsung dari sistem kasir.

## 4. Metrik Keberhasilan

| Metrik | Target |
|---|---|
| Waktu transaksi per order di kasir | Di bawah 30 detik untuk order sederhana |
| Akurasi stok (selisih stok fisik vs sistem) | Di bawah 2% per bulan |
| Waktu order sampai ke dapur | Real-time, di bawah 3 detik |
| Laporan penjualan harian tersedia | Otomatis, tanpa input manual tambahan |
| Adopsi kasir terhadap sistem baru | 100% transaksi tercatat lewat sistem dalam 1 bulan pertama |

## 5. Ruang Lingkup

### 5.1 Termasuk dalam Scope (MVP)

- Point of Sale untuk transaksi dine-in, take away, dan pesan-bawa-pulang.
- Manajemen produk, kategori, dan varian menu (contoh: ukuran, level gula, tambahan topping).
- Manajemen resep atau Bill of Material (BOM) yang menghubungkan menu dengan bahan baku.
- Manajemen stok bahan baku: penerimaan barang, stok masuk, stok keluar, stok opname.
- Manajemen meja dan pemesanan lewat QR code untuk pelanggan dine-in.
- Kitchen Display System (KDS) untuk barista menerima antrian order secara real-time.
- Integrasi payment gateway untuk pembayaran QRIS dinamis, e-wallet, dan kartu.
- Laporan penjualan harian, mingguan, bulanan, dan laporan stok.
- Manajemen pengguna dengan role Owner/Admin, Manager, Kasir, dan Barista/Gudang.
- Manajemen shift kasir dan kas (buka tutup kasir, rekonsiliasi kas).

### 5.2 Tidak Termasuk dalam Scope (Fase Berikutnya)

- Dukungan multi-outlet atau multi-tenant.
- Aplikasi mobile native (Android/iOS). Aplikasi hanya berbasis web responsif.
- Program loyalty atau membership pelanggan.
- Integrasi dengan platform online seperti GoFood atau GrabFood.
- Payroll dan absensi karyawan.

## 6. Target Pengguna dan Role

| Role | Deskripsi Tugas | Akses Utama |
|---|---|---|
| Owner/Admin | Pemilik usaha, mengelola seluruh sistem | Semua modul, termasuk pengaturan sistem dan laporan keuangan |
| Manager/Supervisor | Mengelola operasional harian | Stok, laporan, approve stok masuk/keluar, tidak bisa ubah pengaturan sistem inti |
| Kasir | Melayani transaksi pelanggan | Modul POS, buka tutup shift, cetak struk |
| Barista/Gudang | Mengolah pesanan dan kelola bahan baku | Kitchen Display System, input stok masuk, stok opname |

## 7. Kebutuhan Fungsional

### 7.1 Modul Point of Sale (POS)

- Kasir memilih menu dari daftar kategori dengan tampilan grid, dilengkapi gambar dan harga.
- Kasir bisa menambah catatan khusus per item (contoh: less sugar, extra shot).
- Kasir bisa memilih tipe order: dine-in dengan nomor meja, take away, atau bungkus.
- Sistem menghitung subtotal, pajak (PPN jika aktif), service charge, dan diskon otomatis.
- Kasir bisa menerapkan diskon manual dengan approval Manager jika melebihi batas tertentu.
- Sistem mendukung split payment (sebagian cash, sebagian non-tunai) dalam satu transaksi.
- Kasir mencetak struk lewat thermal printer setelah pembayaran selesai.
- Setiap transaksi otomatis memicu pengurangan stok bahan baku sesuai resep menu yang terjual.
- Kasir wajib membuka shift dengan input modal awal kas sebelum bisa transaksi, dan menutup shift dengan rekonsiliasi kas di akhir shift.
- Sistem mencatat riwayat transaksi yang bisa dicari berdasarkan tanggal, kasir, atau nomor struk.
- Kasir bisa membatalkan atau void transaksi dengan alasan wajib diisi dan approval Manager.

### 7.2 Modul Manajemen Produk dan Menu

- Admin membuat kategori menu (contoh: kopi, non-kopi, makanan, snack).
- Admin membuat produk dengan nama, deskripsi, harga jual, gambar, dan status aktif/nonaktif.
- Admin membuat varian produk (ukuran, level gula, level es) dengan penyesuaian harga per varian.
- Admin bisa menandai menu sebagai habis sementara (out of stock) tanpa menghapus produk.
- Admin mengatur harga khusus untuk periode promosi dengan tanggal mulai dan berakhir.

### 7.3 Modul Manajemen Stok dan Resep (Inventory dan BOM)

- Admin/Gudang mendaftarkan bahan baku dengan satuan (gram, ml, pcs) dan stok minimum.
- Admin membuat resep per menu yang menentukan bahan baku dan takaran yang dipakai.
- Sistem mengurangi stok bahan baku otomatis setiap ada transaksi penjualan sesuai resep.
- Gudang mencatat penerimaan barang dari supplier dengan harga beli dan tanggal kedaluwarsa jika relevan.
- Sistem mengirim notifikasi saat stok bahan baku mendekati atau di bawah batas minimum.
- Gudang melakukan stok opname berkala, sistem mencatat selisih antara stok sistem dan stok fisik.
- Sistem menyimpan riwayat pergerakan stok (kartu stok) per bahan baku: masuk, keluar, dan penyesuaian.
- Admin mengelola data supplier: nama, kontak, dan riwayat pembelian.

### 7.4 Modul Meja, Kitchen Display System, dan QR Order

- Admin mendaftarkan meja dengan nomor dan kode QR unik per meja.
- Pelanggan dine-in memindai QR code di meja untuk membuka menu digital tanpa perlu login.
- Pelanggan memilih menu, menambah ke keranjang, dan mengirim pesanan langsung ke sistem kasir untuk konfirmasi pembayaran.
- Kasir mengonfirmasi dan memproses pembayaran atas order yang masuk dari QR order.
- Setiap order yang sudah dibayar otomatis muncul di layar Kitchen Display System sesuai urutan waktu masuk.
- Barista menandai status order di KDS: diterima, sedang diproses, dan selesai.
- Sistem menampilkan waktu tunggu tiap order di KDS agar barista bisa memprioritaskan order yang lama menunggu.
- Kasir bisa melihat status order dari KDS untuk informasi ke pelanggan.

### 7.5 Modul Pembayaran (Payment Gateway)

- Sistem terintegrasi dengan payment gateway (contoh: Midtrans atau Xendit) untuk QRIS dinamis, e-wallet, dan kartu debit/kredit.
- Sistem menghasilkan QR code pembayaran otomatis sesuai nominal transaksi.
- Sistem menerima notifikasi status pembayaran (callback/webhook) dari payment gateway secara real-time.
- Transaksi berstatus tertunda otomatis dibatalkan jika pembayaran tidak selesai dalam batas waktu tertentu.
- Sistem tetap mendukung pembayaran cash dan QRIS statis manual sebagai metode dasar tanpa gateway.

### 7.6 Modul Laporan Penjualan dan Analitik

- Laporan penjualan harian: total transaksi, total pendapatan, metode pembayaran, dan jumlah item terjual.
- Laporan penjualan per periode (mingguan, bulanan, custom range) dengan grafik tren.
- Laporan menu terlaris dan menu paling sedikit terjual.
- Laporan margin keuntungan per menu berdasarkan harga jual dan harga pokok bahan baku.
- Laporan stok: nilai stok saat ini, bahan baku yang mendekati habis, dan riwayat pergerakan stok.
- Laporan kinerja kasir per shift: jumlah transaksi, total penjualan, dan selisih kas.
- Semua laporan bisa diekspor ke format Excel dan PDF.
- Dashboard ringkasan untuk Owner/Admin menampilkan indikator kunci hari ini: total penjualan, transaksi, dan stok kritis.

### 7.7 Modul Manajemen Pengguna dan Hak Akses

- Admin membuat, mengedit, dan menonaktifkan akun pengguna.
- Sistem membatasi akses fitur sesuai role (role-based access control).
- Sistem mencatat log aktivitas penting: siapa membuat perubahan harga, void transaksi, atau ubah stok.
- Admin mengatur ulang password pengguna jika lupa.

### 7.8 Modul Pengaturan Sistem

- Admin mengatur informasi toko: nama, alamat, nomor telepon, dan logo untuk struk.
- Admin mengatur pajak (PPN) dan service charge, termasuk aktif/nonaktif per jenis order.
- Admin mengatur format nomor struk dan template cetak struk.
- Admin mengatur metode pembayaran yang aktif di kasir.
- Admin mengatur batas stok minimum default dan notifikasi.

## 8. Alur Pengguna Utama

### 8.1 Alur Transaksi di Kasir (Dine-in Manual)

1. Kasir membuka shift dengan input modal kas awal.
2. Pelanggan datang, kasir pilih tipe order dine-in dan nomor meja.
3. Kasir menambahkan menu ke keranjang, sesuaikan varian dan catatan.
4. Kasir menekan bayar, pilih metode pembayaran.
5. Jika non-tunai lewat gateway, sistem tampilkan QR code pembayaran.
6. Pembayaran terkonfirmasi, sistem cetak struk dan kirim order ke Kitchen Display System.
7. Stok bahan baku berkurang otomatis sesuai resep menu yang terjual.

### 8.2 Alur QR Order Mandiri Pelanggan

1. Pelanggan duduk di meja, memindai QR code di meja.
2. Sistem menampilkan menu digital tanpa perlu login.
3. Pelanggan memilih menu dan mengirim pesanan.
4. Kasir menerima notifikasi order baru, verifikasi, dan proses pembayaran ke pelanggan langsung di kasir.
5. Setelah pembayaran lunas, order otomatis masuk ke Kitchen Display System.

### 8.3 Alur Kerja Barista di Kitchen Display System

1. Order baru muncul di layar KDS secara real-time, terurut berdasarkan waktu masuk.
2. Barista menandai order sebagai "sedang diproses".
3. Barista menyelesaikan pesanan dan menandai order sebagai "selesai".
4. Order yang selesai hilang dari antrian aktif dan tersimpan di riwayat.

### 8.4 Alur Stok Opname

1. Gudang memilih periode stok opname.
2. Sistem menampilkan daftar bahan baku dengan stok sistem saat ini.
3. Gudang input stok fisik hasil hitung manual.
4. Sistem menghitung selisih dan menyimpan sebagai catatan penyesuaian stok.
5. Manager approve hasil stok opname sebelum stok sistem diperbarui.

## 9. Kebutuhan Non-Fungsional

| Kategori | Kebutuhan |
|---|---|
| Performa | Halaman POS memuat dalam waktu di bawah 2 detik pada koneksi standar |
| Real-time | Order dari QR dan update KDS memakai koneksi real-time (Laravel Reverb atau Pusher) |
| Keamanan | Password ter-hash, akses API dengan token, HTTPS wajib di produksi |
| Audit | Setiap perubahan data kritis (harga, stok, void transaksi) tercatat dengan waktu dan pelaku |
| Ketersediaan | Sistem bisa diakses selama jam operasional toko tanpa gangguan berarti |
| Kompatibilitas | Tampilan responsif untuk browser desktop di kasir dan browser mobile untuk QR order pelanggan |
| Backup | Database di-backup otomatis setiap hari |
| Skalabilitas | Struktur database siap dikembangkan ke multi-outlet di fase berikutnya tanpa migrasi besar |

## 10. Arsitektur Teknis

### 10.1 Stack Utama

- **Backend Framework**: Laravel (versi LTS terbaru)
- **Frontend Interaktif**: Livewire dengan Alpine.js untuk interaksi ringan di sisi klien
- **Styling**: Tailwind CSS
- **Database**: MySQL
- **Real-time**: Laravel Reverb (WebSocket) untuk update Kitchen Display System dan notifikasi order baru
- **Autentikasi**: Laravel Breeze atau Fortify dengan Livewire
- **Hak Akses**: Spatie Laravel Permission untuk role dan permission
- **Payment Gateway**: Midtrans atau Xendit lewat package resmi PHP/Laravel
- **Cetak Struk**: Integrasi thermal printer lewat browser print atau ESC/POS lewat aplikasi bridge lokal
- **Export Laporan**: Maatwebsite Excel untuk Excel, DomPDF atau Snappy PDF untuk PDF
- **Antrian Job**: Laravel Queue untuk proses webhook payment gateway dan notifikasi stok

### 10.2 Pertimbangan Arsitektur

- QR order pelanggan diakses lewat rute publik tanpa autentikasi, dengan session sementara per meja untuk mencegah pesanan tercampur antar pelanggan.
- Setiap transaksi penjualan dan pengurangan stok berjalan dalam satu database transaction agar data tetap konsisten jika terjadi kegagalan di tengah proses.
- Webhook dari payment gateway diproses lewat queue agar respons ke gateway tetap cepat dan tidak memblokir proses lain.
- Struktur database dirancang dengan kolom outlet_id sejak awal meski saat ini single outlet, supaya migrasi ke multi-outlet di fase berikutnya lebih mudah.

## 11. Model Data (Ringkasan Entitas)

| Entitas | Deskripsi Singkat |
|---|---|
| users | Akun pengguna sistem dengan role |
| roles, permissions | Hak akses berbasis role |
| categories | Kategori menu |
| products | Data menu/produk |
| product_variants | Varian produk (ukuran, level gula, dll) |
| ingredients | Bahan baku |
| recipes (BOM) | Relasi produk/varian dengan bahan baku dan takaran |
| suppliers | Data pemasok bahan baku |
| stock_movements | Riwayat pergerakan stok (masuk, keluar, penyesuaian) |
| stock_opnames | Catatan hasil stok opname |
| tables | Data meja dan kode QR |
| orders | Data pesanan (dine-in, take away, QR order) |
| order_items | Detail item per pesanan |
| transactions | Data transaksi pembayaran |
| payments | Detail metode dan status pembayaran, termasuk data dari payment gateway |
| shifts | Data buka tutup shift kasir |
| discounts | Data diskon dan promosi |
| settings | Pengaturan sistem (pajak, service charge, info toko) |
| activity_logs | Log audit aktivitas pengguna |

### Relasi Kunci

- Satu produk bisa punya banyak varian, satu varian punya satu resep dengan banyak bahan baku.
- Satu order punya banyak order item, satu order punya satu transaksi pembayaran.
- Satu transaksi memicu banyak stock movement, satu per bahan baku yang terpakai.
- Satu shift kasir punya banyak transaksi selama periode shift berjalan.

## 12. Daftar Halaman per Role

| Role | Halaman Utama |
|---|---|
| Kasir | Login, Buka Shift, Layar POS (grid menu dan keranjang), Konfirmasi Pembayaran, Riwayat Transaksi, Tutup Shift |
| Barista/Gudang | Kitchen Display System, Daftar Bahan Baku, Form Penerimaan Barang, Form Stok Opname |
| Manager | Dashboard Ringkasan, Approval Diskon/Void, Approval Stok Opname, Laporan Penjualan, Laporan Stok |
| Owner/Admin | Semua halaman Manager ditambah Manajemen Produk, Manajemen Resep, Manajemen Pengguna, Pengaturan Sistem |
| Pelanggan (QR Order) | Halaman Menu Digital per Meja, Keranjang, Konfirmasi Pesanan |

## 13. Fase Pengembangan

### Fase 1: MVP (Prioritas Utama)

- Modul POS dasar dengan pembayaran cash dan QRIS manual.
- Manajemen produk, kategori, dan varian.
- Manajemen stok dan resep dengan pengurangan otomatis.
- Manajemen shift kasir.
- Laporan penjualan dan stok dasar.
- Manajemen pengguna dan role.

### Fase 2: Fitur Lanjutan

- Kitchen Display System real-time.
- QR order mandiri untuk pelanggan dine-in.
- Integrasi payment gateway online (QRIS dinamis, e-wallet, kartu).
- Laporan margin keuntungan dan analitik lanjutan.

### Fase 3: Pengembangan Jangka Panjang (Di Luar Scope Saat Ini)

- Dukungan multi-outlet.
- Program membership dan loyalty pelanggan.
- Integrasi platform online delivery.

## 14. Asumsi dan Batasan

- Aplikasi hanya untuk satu outlet pada versi ini.
- Pengguna mengakses aplikasi lewat browser, tidak ada aplikasi native.
- Printer struk yang dipakai kompatibel dengan standar ESC/POS atau print browser biasa.
- Koneksi internet toko stabil untuk mendukung fitur real-time dan payment gateway.
- Pemilihan payment gateway final (Midtrans/Xendit/lainnya) ditentukan sebelum masuk tahap development integrasi pembayaran.

## 15. Risiko dan Mitigasi

| Risiko | Dampak | Mitigasi |
|---|---|---|
| Koneksi internet toko tidak stabil | Payment gateway dan QR order gagal berfungsi | Sediakan metode cash dan QRIS statis manual sebagai cadangan |
| Resep/BOM tidak diisi lengkap oleh admin | Stok tidak berkurang akurat | Validasi sistem: produk tidak bisa dijual sebelum resep dilengkapi |
| Kasir baru kesulitan adaptasi | Transaksi lambat di awal | Sediakan sesi pelatihan dan tampilan POS yang sederhana |
| Webhook payment gateway telat diterima | Status transaksi tidak update tepat waktu | Tambahkan mekanisme pengecekan status manual oleh kasir sebagai cadangan |

## 16. Pertanyaan Terbuka untuk Tahap Berikutnya

Detail berikut perlu dikonfirmasi sebelum masuk tahap desain teknis dan UI mendetail:

1. Payment gateway mana yang dipilih: Midtrans, Xendit, atau lainnya.
2. Apakah dibutuhkan cetak struk lewat printer thermal fisik, atau cukup cetak lewat browser/PDF.
3. Referensi tampilan atau contoh aplikasi POS yang disukai untuk gaya visual (warna, layout).
4. Apakah pajak (PPN) wajib aktif secara default atau opsional per transaksi.
5. Berapa lama masa retensi data transaksi dan log yang perlu disimpan di database.

---

*Dokumen ini adalah draft awal. Detail tampilan (wireframe/mockup) dan spesifikasi API akan disusun setelah PRD ini disetujui.*
