<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantOrder extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merchant_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'users_id',
        'merchant_entity_reference',
        'order_id',
        'transaction_id',
        'shopify_order_id',
        'shopify_transaction_id',
        'g_id',
        'order_status',
        'purchase_date',
        'products_data',
        'email',
        'phone_no',
        'billing_name',
        'shipping_name',
        'billing_address',
        'shipping_address',
        'total_amount',
        'payment_type',
        'locale',
        'currency',
        'shopify_group',
        'test_order',
        'order_type',
        'shopify_shop_domain',
        'shopify_api_version',
        'shopify_request_id',
        'created_at',
        'updated_at',
    ];

}
