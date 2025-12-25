<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('code')->nullable()->after('category_id');
            $table->integer('min_stock')->default(0)->after('stock');
        });
        
        // Update existing products with default codes
        DB::statement("UPDATE products SET code = 'PROD' || id WHERE code IS NULL");
        
        // Make code column unique and not null
        Schema::table('products', function (Blueprint $table) {
            $table->string('code')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['code', 'min_stock']);
        });
    }
};
