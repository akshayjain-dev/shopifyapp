<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\CommonTrait;
use App\Http\Traits\MerchantOrdersTrait;
use App\Models\MerchantOrders;
use App\Models\Order;
use App\Models\User;

class DropshipOrdersController extends Controller
{
    use MerchantOrdersTrait;
    use CommonTrait;

    public function performAction(Request $request)
    {
        if ($request->isMethod('patch')) {
       
            if ($request->input('dropship_orders')) {
                $dropshipOrders = array_filter(explode(',',$request->input('dropship_orders')));
                if(!empty($dropshipOrders)){
                    $orders = Order::whereIn('id', $dropshipOrders)->get();
                    $order_data = [];
                    $i=0;
                    foreach($orders as  $order){
                        $billingAddress = json_decode($order->billing_address,true);
                        $shippingAddress = json_decode($order->shipping_address,true);



                        $order_data[$i]['date_created'] = $order->purchase_date;
                        $order_data[$i]['date_modified'] = $order->purchase_date;
                        $order_data[$i]['billing'] = [
                            'first_name' => $billingAddress['first_name'],
                            'last_name' => $billingAddress['last_name'],
                            'company' => $billingAddress['company'],
                            'address_1' => $billingAddress['address1'],
                            'address_2' => $billingAddress['address2'],
                            'city' => $billingAddress['city'],
                            'state' => $billingAddress['province'],
                            'postcode' => $billingAddress['zip'],
                            'country' => $billingAddress['country'],
                            'email' => $order->email,
                            'phone' => $order->phone_no

                        ];
                        $order_data[$i]['shipping'] = [
                             'first_name' => $shippingAddress['first_name'],
                            'last_name' => $shippingAddress['last_name'],
                            'company' => $shippingAddress['company'],
                            'address_1' => $shippingAddress['address1'],
                            'address_2' => $shippingAddress['address2'],
                            'city' => $shippingAddress['city'],
                            'state' => $shippingAddress['province'],
                            'postcode' => $shippingAddress['zip'],
                            'country' => $shippingAddress['country'],
                            'phone' => $order->phone_no
                        ];
                        $order_data[$i]['total'] = $order->total_amount;
                        $order_data[$i]['shopify_shop_domain'] = $order->shopify_shop_domain;

                        $order_item = [];
                        if(!empty($order->products_data)){
                            $orderproductsData = json_decode($order->products_data,true);
                            foreach($orderproductsData as $j=>$productsData){
                                $order_item[$j]['product_id']= $productsData['product_id'];
                                $order_item[$j]['sku'] = $productsData['sku'];
                                $order_item[$j]['variation_id'] = $productsData['variant_id'];
                                $order_item[$j]['name'] = $productsData['name'];
                                $order_item[$j]['quantity'] = $productsData['quantity'];
                                $order_item[$j]['subtotal'] = (int)$productsData['quantity'] * (float)$productsData['price'];
                                $order_item[$j]['total'] = (int)$productsData['quantity'] * (float)$productsData['price'];
                                $order_item[$j]['tax'] = 0;
                                $order_item[$j]['taxclass'] = 0;
                                $order_item[$j]['taxstat'] = '';
                                $order_item[$j]['type'] = '';
                                $j++;

                            }

                        }
                        $order_data[$i]['items'] = $order_item;
                      $i++;
                    }



                  $dropshipdashboardUrl =  $this->importOrderAndRedirect($order_data);
                
                  return redirect()->away($dropshipdashboardUrl);

                }

            }
            return redirect()->route('orders/index');

        }

        return redirect()->route('orders/index');

    }
}
