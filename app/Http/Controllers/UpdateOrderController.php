<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MerchantOrders;

class UpdateOrderController extends Controller
{
    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'status' => 'required',
        ]);

        $order = MerchantOrders::where('order_id', $request->input('order_id'))->first();

        if ($order) {
            $order->update(['order_status' => $request->input('status')]);

            $this->updateShopifyOrderStatus($order, $request->input('status'));

            return response()->json(['message' => 'Order status updated successfully']);
        } else {
            return response()->json(['message' => 'Order not found'], 404);
        }
    }

    private function updateShopifyOrderStatus($order, $status)
    {
        $orderID = basename($order->g_id);
        if($orderID)
        {
            $shopifyApiUrl = 'https://'.$order->shopify_shop_domain.'/admin/api/2023-10/orders/'.$orderID.'.json';

                $clientId = '7449656d11f89ab0930086172c75ee3d';
                $clientSecret = '65e1c42b1c2a31b00c13e06f7ac3c9e0';
        
                $base64Credentials = base64_encode("$clientId:$clientSecret");
                $shopifyApiHeaders = [
                    'X-Shopify-Access-Token' => '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',
                    'Content-Type' => 'application/json',
                ];
        
                $shopifyApiData = [
                    'order' => [
                        'id' => $orderID,
                        'status' => $status,
                    ],
                ];
                $client = new \GuzzleHttp\Client();
        
                try {
                    $response = $client->request('PUT', $shopifyApiUrl, [
                        'headers' => $shopifyApiHeaders,
                        'json' => $shopifyApiData,
                    ]);
        
                    $statusCode = $response->getStatusCode();
                    $responseBody = $response->getBody()->getContents();
                    dd($responseBody);
        
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
        }
        
    }
}
