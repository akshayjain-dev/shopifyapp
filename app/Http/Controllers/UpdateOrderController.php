<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MerchantOrder;

class UpdateOrderController extends Controller
{
    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'status' => 'required',
        ]);

        $order = MerchantOrder::where('order_id', $request->input('order_id'))->first();

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

                $clientId = '2c00498021b0838830faeea664995079';
                $clientSecret = 'f6d512d1c63bad51d0a6e31cd4c3e797';
        
                $base64Credentials = base64_encode("$clientId:$clientSecret");
                $shopifyApiHeaders = [
                    'Authorization' => 'Basic ' . $base64Credentials,
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
