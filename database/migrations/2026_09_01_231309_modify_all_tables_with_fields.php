<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. categories
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('name');
        });

        // 2. products
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->onDelete('cascade')->after('id');
            $table->string('name')->after('category_id');
            $table->text('description')->nullable()->after('name');
            $table->decimal('price', 10, 2)->after('description');
            $table->string('image')->nullable()->after('price');
            $table->boolean('is_active')->default(true)->after('image');
            $table->boolean('out_of_stock')->default(false)->after('is_active');
        });

        // 3. product_variants
        Schema::table('product_variants', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->after('id');
            $table->string('name')->after('product_id'); 
            $table->string('value')->after('name'); 
            $table->decimal('price_adjustment', 10, 2)->default(0)->after('value');
        });

        // 4. ingredients
        Schema::table('ingredients', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('unit')->after('name'); 
            $table->decimal('minimum_stock', 10, 2)->default(0)->after('unit');
            $table->decimal('current_stock', 10, 2)->default(0)->after('minimum_stock');
        });

        // 5. recipes (BOM)
        Schema::table('recipes', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade')->after('product_id');
            $table->string('name')->after('product_variant_id'); 
        });

        // 6. recipe_ingredients
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade')->after('recipe_id');
            $table->decimal('quantity', 10, 2)->after('ingredient_id'); 
        });

        // 7. suppliers
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('contact_name')->nullable()->after('name');
            $table->string('phone')->nullable()->after('contact_name');
            $table->string('email')->nullable()->after('phone');
            $table->text('address')->nullable()->after('email');
        });

        // 8. stock_movements
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('ingredient_id');
            $table->enum('type', ['in', 'out', 'adjustment'])->after('user_id');
            $table->decimal('quantity', 10, 2)->after('type');
            $table->string('reference_type')->nullable()->after('quantity'); 
            $table->unsignedBigInteger('reference_id')->nullable()->after('reference_type');
            $table->text('notes')->nullable()->after('reference_id');
        });

        // 9. stock_opnames
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id'); 
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null')->after('user_id'); 
            $table->date('opname_date')->after('manager_id');
            $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft')->after('opname_date');
            $table->text('notes')->nullable()->after('status');
        });

        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('system_stock', 10, 2);
            $table->decimal('actual_stock', 10, 2);
            $table->decimal('difference', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 10. tables
        Schema::table('tables', function (Blueprint $table) {
            $table->string('number')->after('id');
            $table->string('qr_code_url')->nullable()->after('number');
            $table->enum('status', ['available', 'occupied', 'reserved'])->default('available')->after('qr_code_url');
        });

        // 11. shifts
        Schema::table('shifts', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id'); 
            $table->dateTime('start_time')->after('user_id');
            $table->dateTime('end_time')->nullable()->after('start_time');
            $table->decimal('starting_cash', 12, 2)->default(0)->after('end_time');
            $table->decimal('ending_cash', 12, 2)->nullable()->after('starting_cash');
            $table->decimal('expected_cash', 12, 2)->nullable()->after('ending_cash');
            $table->decimal('difference', 12, 2)->nullable()->after('expected_cash');
            $table->text('notes')->nullable()->after('difference');
            $table->enum('status', ['open', 'closed'])->default('open')->after('notes');
        });

        // 12. orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->unique()->after('id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('order_number'); 
            $table->foreignId('table_id')->nullable()->constrained()->onDelete('set null')->after('user_id');
            $table->foreignId('shift_id')->nullable()->constrained()->onDelete('set null')->after('table_id

');
            $table->enum('type', ['dine-in', 'take-away', 'delivery', 'qr-order'])->after('shift_id');
            $table->string('customer_name')->nullable()->after('type');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending')->after('customer_name');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid')->after('status');
            $table->decimal('subtotal', 12, 2)->default(0)->after('payment_status');
            $table->decimal('tax_amount', 12, 2)->default(0)->after('subtotal');
            $table->decimal('service_charge_amount', 12, 2)->default(0)->after('tax_amount');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('service_charge_amount');
            $table->decimal('total', 12, 2)->default(0)->after('discount_amount');
            $table->text('notes')->nullable()->after('total');
        });

        // 13. order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->after('order_id');
            $table->json('variants')->nullable()->after('product_id'); 
            $table->integer('quantity')->after('variants');
            $table->decimal('price', 12, 2)->after('quantity'); 
            $table->decimal('subtotal', 12, 2)->after('price');
            $table->text('notes')->nullable()->after('subtotal');
            $table->enum('status', ['pending', 'preparing', 'ready', 'served'])->default('pending')->after('notes');
        });

        // 14. payments
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->after('id');
            $table->string('payment_method')->after('order_id'); 
            $table->decimal('amount', 12, 2)->after('payment_method');
            $table->enum('status', ['pending', 'success', 'failed'])->default('success')->after('amount');
            $table->string('reference_number')->nullable()->after('status'); 
            $table->json('gateway_response')->nullable()->after('reference_number');
        });
        
        // 15. transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('shift_id')->nullable()->constrained()->onDelete('set null')->after('id');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null')->after('shift_id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('order_id');
            $table->enum('type', ['income', 'expense'])->after('user_id');
            $table->string('category')->after('type'); 
            $table->decimal('amount', 12, 2)->after('category');
            $table->string('payment_method')->nullable()->after('amount');
            $table->text('description')->nullable()->after('payment_method');
        });

        // 16. discounts
        Schema::table('discounts', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('code')->nullable()->after('name');
            $table->enum('type', ['percentage', 'fixed'])->after('code');
            $table->decimal('value', 10, 2)->after('type');
            $table->dateTime('start_date')->nullable()->after('value');
            $table->dateTime('end_date')->nullable()->after('start_date');
            $table->boolean('is_active')->default(true)->after('end_date');
            $table->decimal('minimum_purchase', 12, 2)->default(0)->after('is_active');
        });

        // 17. settings
        Schema::table('settings', function (Blueprint $table) {
            $table->string('key')->unique()->after('id');
            $table->text('value')->nullable()->after('key');
            $table->string('type')->default('string')->after('value'); 
            $table->string('group')->default('general')->after('type');
        });

        // 18. activity_logs
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('id');
            $table->string('action')->after('user_id'); 
            $table->string('model_type')->nullable()->after('action'); 
            $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            $table->json('old_data')->nullable()->after('model_id');
            $table->json('new_data')->nullable()->after('old_data');
            $table->string('ip_address')->nullable()->after('new_data');
            $table->text('user_agent')->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
    }
};
