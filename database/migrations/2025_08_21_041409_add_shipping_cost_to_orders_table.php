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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('estimated_shipping_cost', 10, 2)->default(0)->after('total_amount');
            $table->decimal('final_shipping_cost', 10, 2)->nullable()->after('estimated_shipping_cost');
            $table->boolean('shipping_confirmed')->default(false)->after('final_shipping_cost');
            $table->text('shipping_notes')->nullable()->after('shipping_confirmed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['estimated_shipping_cost', 'final_shipping_cost', 'shipping_confirmed', 'shipping_notes']);
        });
    }
};
