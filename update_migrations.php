<?php
$content = <<<'EOD'
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
            $table->string('name');
            $table->text('description')->nullable();
        });

        // 2. products
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('out_of_stock')->default(false);
        });

        // 3. product_variants
        Schema::table('product_variants', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name'); 
            $table->string('value'); 
            $table->decimal('price_adjustment', 10, 2)->default(0);
        });

        // 4. ingredients
        Schema::table('ingredients', function (Blueprint $table) {
            $table->string('name');
            $table->string('unit'); 
            $table->decimal('minimum_stock', 10, 2)->default(0);
            $table->decimal('current_stock', 10, 2)->default(0);
        });

        // 5. recipes (BOM)
        Schema::table('recipes', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name'); 
        });

        // 6. recipe_ingredients
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2); 
        });

        // 7. suppliers
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('name');
            $table->string('contact_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
        });

        // 8. stock_movements
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->decimal('quantity', 10, 2);
            $table->string('reference_type')->nullable(); 
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
        });

        // 9. stock_opnames
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null'); 
            $table->date('opname_date');
            $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft');
            $table->text('notes')->nullable();
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
            $table->string('number');
            $table->string('qr_code_url')->nullable();
            $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
        });

        // 11. shifts
        Schema::table('shifts', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->decimal('starting_cash', 12, 2)->default(0);
            $table->decimal('ending_cash', 12, 2)->nullable();
            $table->decimal('expected_cash', 12, 2)->nullable();
            $table->decimal('difference', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
        });

        // 12. orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); 
            $table->foreignId('table_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('shift_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['dine-in', 'take-away', 'delivery', 'qr-order']);
            $table->string('customer_name')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('service_charge_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('notes')->nullable();
        });

        // 13. order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->json('variants')->nullable(); 
            $table->integer('quantity');
            $table->decimal('price', 12, 2); 
            $table->decimal('subtotal', 12, 2);
            $table->text('notes
