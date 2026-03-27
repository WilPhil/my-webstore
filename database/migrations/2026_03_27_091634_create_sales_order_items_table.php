<?php

use App\Models\SalesOrder;
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
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SalesOrder::class)->constrained();

            $table->string('name');
            $table->string('slug');
            $table->string('sku');
            $table->string('tags');
            $table->text('description')->nullable();
            $table->string('cover_url')->nullable();
            $table->integer('stock');
            $table->integer('weight');
            $table->double('price', 11, 2);
            $table->double('total', 11, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
    }
};
