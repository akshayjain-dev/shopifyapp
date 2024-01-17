<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Http\Traits\MerchantOrdersTrait;
use App\Models\MerchantOrders;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrdersController extends Controller
{
    use MerchantOrdersTrait;
    use CommonTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('verify.shopify');
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $users_id = 2; // Hardcoded order ID
        $paginate = 10;
        $order_by_field = 'id';
        $order_asc_desc = 'desc';
        $merchant_name = '';
        if (empty($merchant_entity_reference)) {
            $orders = Order::where('id', '!=', '');
        } else {
            $orders = Order::where('users_id', '=', $users_id);
        }
        if ($request->isMethod('post')) {
            if ($request->filled('search_data')) {
                $keyword = $request->input('search_data');
                $orders = $orders->where(
                    function ($query) use ($keyword) {
                        $query->where(
                            'billing_name',
                            'like',
                            '%'.$keyword.'%'
                        );
                    }
                );
            }
            if ($request->filled('merchant_name')) {
                $merchant_name = User::where(
                    'id',
                    $request->input('merchant_name')
                )
                    ->value('name');
                $orders = $orders->where(
                    'users_id',
                    '=',
                    $request->input('merchant_name')
                );
            }
            if ($request->filled('date_from')) {
                $new_date_from
                    = \Carbon\Carbon::createFromFormat(
                        'd-m-Y',
                        $request->input('date_from')
                    )
                        ->format('Y-m-d');
                $orders = $orders->whereDate(
                    'purchase_date',
                    '>=',
                    $new_date_from
                );
            }
            if ($request->filled('date_to')) {
                $new_date_to
                    = \Carbon\Carbon::createFromFormat(
                        'd-m-Y',
                        $request->input('date_to')
                    )
                        ->format('Y-m-d');
                $orders = $orders->whereDate(
                    'purchase_date',
                    '<=',
                    $new_date_to
                );
            }
            if ($request->filled('status')) {
                $orders = $orders->where(
                    'order_status',
                    '=',
                    $request->input('status')
                );
            }
            if ($request->filled('pagination')) {
                $paginate = $request->input('pagination');
            }
            if ($request->filled('order_by_asc_desc')) {
                $order_asc_desc = $request->input('order_by_asc_desc');
                if ($order_asc_desc === 'true') {
                    $order_asc_desc = 'asc';
                } elseif ($order_asc_desc === 'false') {
                    $order_asc_desc = 'desc';
                }
            }
            if ($request->filled('order_by_field')) {
                switch ($request->input('order_by_field')) {
                    case 'price':
                        $order_by_field = 'total_amount';
                        break;
                    case 'id':
                        $order_by_field = 'id';
                        break;
                    case 'order_id':
                        $order_by_field = 'order_id';
                        break;
                    case 'date':
                        $order_by_field = 'purchase_date';
                        break;
                    case 'email':
                        $order_by_field = 'email';
                        break;
                    case 'shipname':
                        $order_by_field = 'shipping_address';
                        break;
                    case 'billname':
                        $order_by_field = 'billing_address';
                        break;
                    case 'status':
                        $order_by_field = 'order_status';
                        break;
                    case 'phone':
                        $order_by_field = 'phone_no';
                        break;
                }
            }
        }
        $count = $orders->count();
        $orders = $orders->orderBy($order_by_field, $order_asc_desc)
            ->paginate($paginate);

        
        return view('ssltd_dashboard.orders.index', compact('orders', 'merchant_name'))
            ->with('count', $count);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Show the form for edit resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param $order order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order): View
    {
        $orderOms = new Order();
        $order_oms = $orderOms::where('id', $order->id)->first();

        
       // $installedApp = $this->installedApp($order);
        $refund_requests = [];
        $remaining_amount = '';
        $capture_requests = [];
        $capture_remaining_amount = '';

        return view(
            'ssltd_dashboard.orders.show',
            compact(
                'order',
                'order_oms'
            )
        )
            ->with('billing_address', json_decode($order->billing_address, true))
            ->with('shipping_address', json_decode($order->shipping_address, true));
    }

    /**
     * Check App is installed or not .
     *
     * @param $order order
     * @return \Illuminate\Http\Response
     */
    public function installedApp($order)
    {
        $shopDomain = $order->shopify_shop_domain;
        $deleted = User::where('name', $shopDomain)->first();
        if (! empty($deleted)) {
            return true;
        } else {
            return false;
        }
    }
}