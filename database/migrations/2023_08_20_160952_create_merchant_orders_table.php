<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('merchant_entity_reference');
            $table->string('order_id');
            $table->string('transaction_id');
            $table->string('g_id');
            $table->string('order_status', 50);
            $table->dateTimeTz('purchase_date', $precision = 0);
            $table->string('email');
            $table->string('phone_no');
            $table->string('billing_name')->nullable();
            $table->string('shipping_name')->nullable();
            $table->text('billing_address');
            $table->text('shipping_address');
            $table->double('total_amount', 8, 2);
            $table->string('locale', 5);
            $table->string('currency', 5);
            $table->string('shopify_group');
            $table->boolean('test_order')->default(0);
            $table->string('order_type');
            $table->string('shopify_shop_domain');
            $table->string('shopify_api_version');
            $table->string('shopify_request_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_orders');
    }
}
