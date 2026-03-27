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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();

            $table->string('trx_id')->unique();
            $table->string('status');

            // Customer Data
            $table->string('customer_full_name');
            $table->string('customer_email_address');
            $table->string('customer_phone_number');

            $table->string('address_line');

            // Region Data - origin
            $table->string('origin_code');
            $table->string('origin_province');
            $table->string('origin_regency');
            $table->string('origin_district');
            $table->string('origin_village');
            $table->string('origin_postal_code');

            // Region Data - destination
            $table->string('destination_code');
            $table->string('destination_province');
            $table->string('destination_regency');
            $table->string('destination_district');
            $table->string('destination_village');
            $table->string('destination_postal_code');

            // Shipping Data
            $table->string('shipping_driver');
            $table->string('shipping_receipt_number');
            $table->string('shipping_courier');
            $table->string('shipping_service');
            $table->string('shipping_estimated_delivery');
            $table->double('shipping_cost', 11, 2);
            $table->integer('shipping_weight');

            // Payment Data
            $table->string('payment_driver');
            $table->string('payment_method');
            $table->string('payment_label');
            $table->json('payment_payload');
            $table->timestamp('payment_paid_at')->nullable();

            $table->double('sub_total', 11, 2);
            $table->double('shipping_total', 11, 2);
            $table->double('grand_total', 11, 2);

            $table->timestamp('due_date_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
