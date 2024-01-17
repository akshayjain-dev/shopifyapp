<?php

/**
 * MerchantOrdersTrait
 * php version 8.1
 *
 * @category  MerchantOrdersTrait
 *
 * @author    SSLTD <mail@company.com>
 * @license   see LICENSE
 *
 * @link      https://dropshipwebhosting.co.uk/
 */

namespace App\Http\Traits;

use App\Models\MerchantOrders;
use App\Models\User;

trait MerchantOrdersTrait
{
    /**
     * Get Order Details
     *
     * @param $orderid order id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getOrderDetails($orderid)
    {
        $response = MerchantOrders::where('order_id', $orderid)->first();

        return $response;
    }

    /**
     * Get Merchant Details
     *
     * @param $shopify_shop_domain shop domain
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAccessToken($shopify_shop_domain)
    {
        $response = User::where('name', $shopify_shop_domain)->first();
        if (empty($response)) {
            return false;
        }

        return $response;
    }

    /**
     * Save new order
     *
     * @param $orderdata Order data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function saveNewOrder($orderdata)
    {
        $orderdata = json_decode($orderdata, false);
        $shopOrder = new MerchantOrders();
        $shopOrder->users_id = $orderdata->users_id;
        $shopOrder->merchant_entity_reference = $orderdata
            ->merchant_entity_reference;
        $shopOrder->order_id = $orderdata->order_id;
        $shopOrder->g_id = $orderdata->g_id;
        $shopOrder->transaction_id = $orderdata->transaction_id;
        $shopOrder->order_status = $orderdata->order_status;
        $shopOrder->email = $orderdata->email;
        $shopOrder->phone_no = $orderdata->phone_no;
        $shopOrder->billing_address = $orderdata->billing_address;
        $shopOrder->shipping_address = $orderdata->shipping_address;
        $shopOrder->total_amount = $orderdata->total_amount;
        $shopOrder->locale = $orderdata->locale;
        $shopOrder->currency = $orderdata->currency;
        $shopOrder->billing_name = $orderdata->billing_name;
        $shopOrder->shipping_name = $orderdata->shipping_name;
        $shopOrder->shopify_group = $orderdata->shopify_group;
        $shopOrder->test_order = $orderdata->test_order;
        $shopOrder->order_type = $orderdata->order_type;
        $shopOrder->redirect_cancel_url = $orderdata->redirect_cancel_url;
        $shopOrder->shopify_shop_domain = $orderdata->shopify_shop_domain;
        $shopOrder->shopify_api_version = $orderdata->shopify_api_version;
        $shopOrder->shopify_request_id = $orderdata->shopify_request_id;
        $shopOrder->purchase_date = $orderdata->purchase_date;
        $response = $shopOrder->save();

        return $response;
    }
    /**
     * Update order status
     *
     * @param $orderid    id
     * @param $orderarray order array
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateOrderStatus($orderid, $orderarray)
    {
        $response = MerchantOrders::where('order_id', $orderid)
            ->update($orderarray);

        return $response;
    }

    /**
     * Update order status
     *
     * @param $userId           user id
     * @param $merchantEntity   merchant entity
     * @param $shopDomain       shop domain
     * @param $apiVersion       Api version
     * @param $ShopifyRequestId Shopify Request Id
     * @param $orderdata        order data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function orderData($userId, $shopDomain, $apiVersion, $orderdata)
    {
        $customer = json_decode(json_encode($orderdata->customer));

        $email = isset($customer->email)
            ? $customer->email : ' ';
        $phone = isset($customer->phone_number)
            ? $customer->phone_number : ' ';
        $billingName = isset($customer->billing_address->family_name)
            ? $customer->billing_address->family_name : ' ';
        if (isset($customer->billing_address->given_name)) {
            $billingName = $customer->billing_address->given_name.' '.$billingName;
        }
        $shippingName = isset($customer->shipping_address->family_name)
            ? $customer->shipping_address->family_name : ' ';
        if (isset($customer->shipping_address->given_name)) {
            $shippingName = $customer->shipping_address->given_name.' '.$shippingName;
        }
        $data = [
            'shopify_order_id' => $orderdata->id,
            'users_id' => $userId,
            'merchant_entity_reference' => 'NULL',
            'order_id' => $orderdata->order_number,
            'g_id' => $orderdata->admin_graphql_api_id,
            'transaction_id' => '',
            'order_status' => $orderdata->fulfillment_status,
            'billing_name' => $billingName,
            'shipping_name' => $shippingName,
            'test_order' => $orderdata->test,
            'redirect_cancel_url' => '',
            'shopify_group' => '',
            'order_type' => '',
            'email' => $email,
            'phone_no' => $phone,
            'billing_address' => json_encode($orderdata->billing_address),
            'shipping_address' => json_encode($orderdata->shipping_address),
            'total_amount' => $orderdata->current_total_price,
            'locale' => $orderdata->customer_locale,
            'currency' => $orderdata->currency,
            'shopify_shop_domain' => $shopDomain,
            'shopify_api_version' => $apiVersion,
            'shopify_request_id' => '',
            'purchase_date' => date('Y-m-d H:i:s', strtotime($orderdata->created_at)),
        ];
        $this->saveNewOrder(json_encode($data));
    }
}