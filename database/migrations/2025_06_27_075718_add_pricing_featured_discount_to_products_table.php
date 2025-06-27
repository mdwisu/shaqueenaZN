<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('pricing_mode', ['manual', 'auto'])->default('manual')->after('cost_price');
            $table->decimal('markup_percent', 5, 2)->nullable()->after('pricing_mode');
            $table->boolean('is_featured')->default(false)->after('markup_percent');
            $table->date('featured_start')->nullable()->after('is_featured');
            $table->date('featured_end')->nullable()->after('featured_start');
            $table->enum('discount_type', ['percent', 'nominal'])->nullable()->after('featured_end');
            $table->decimal('discount_value', 10, 2)->nullable()->after('discount_type');
            $table->date('discount_start')->nullable()->after('discount_value');
            $table->date('discount_end')->nullable()->after('discount_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'pricing_mode',
                'markup_percent',
                'is_featured',
                'featured_start',
                'featured_end',
                'discount_type',
                'discount_value',
                'discount_start',
                'discount_end',
            ]);
        });
    }
};
