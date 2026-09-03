<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->decimal('unit_cost', 14, 2)->nullable()->after('quantity');
            $table->date('expiry_date')->nullable()->after('unit_cost');
            $table->decimal('total_cost', 14, 2)->nullable()->after('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn(['unit_cost', 'expiry_date', 'total_cost']);
        });
    }
};