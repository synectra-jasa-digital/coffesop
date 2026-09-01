# Good Coffee - POS & KDS System

Sistem Point of Sale dan Kitchen Display System terpadu untuk coffee shop.

## Tech Stack
- Laravel 11
- Livewire 3
- Tailwind CSS (Tailwind UI inspired design system)
- Spatie Permission

## Update Terakhir (2 September 2026)

### Selesai Dikerjakan:
1. Setup project Laravel
2. Install Laravel Breeze (Livewire) untuk autentikasi
3. Install Spatie Laravel Permission
4. Membuat Models & Migrations lengkap sesuai PRD:
   - Categories, Products, ProductVariants
   - Ingredients, Recipes (BOM), RecipeIngredients, Suppliers
   - StockMovements, StockOpnames, StockOpnameDetails
   - Tables
   - Shifts, Orders, OrderItems, Payments, Transactions
   - Discounts, Settings, ActivityLogs
5. Setup RBAC (Roles & Permissions Seeder)
   - Owner/Admin, Manager/Supervisor, Kasir, Barista/Gudang
6. Design System Implementation (Tailwind)
   - Konfigurasi warna, tipografi, dan border radius sesuai PRD UI
   - Pembuatan reusable UI components (`x-ui.button`, `x-ui.card`, `x-ui.heading`, `x-ui.section`)
7. Pembuatan Halaman Inti (Livewire Components):
   - Landing Page (`/`)
   - Dashboard (`/dashboard`)
   - POS Terminal (`/pos`) dengan layout khusus kasir & integrasi state cart dasar
   - Kitchen Display System (`/kds`) dengan layout khusus dapur
   - Halaman Admin Menu/Produk (`/admin/products`)
   - Halaman Admin Stok/Inventori (`/admin/inventory`)
8. Model Relations:
   - Relasi standar `belongsTo` / `hasMany` diimplementasikan di semua Model

### To-Do Selanjutnya:
1. **Logika Bisnis POS**: Mengisi cart dari database produk real dan implementasi proses checkout/pembayaran, pengurangan stok berdasar BOM.
2. **Real-time KDS**: Setup Laravel Reverb & konfigurasi websocket (Pusher/Reverb) untuk render pesanan secara real-time ke layar KDS.
3. **CRUD Produk & Stok**: Melengkapi Livewire class untuk insert/update data produk, varian, resep, dan pergerakan stok.
4. **Middleware Guard**: Menerapkan role-based access control pada masing-masing rute sesuai Role Spatie yang sudah dibuat.
