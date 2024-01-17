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

class DropshipDashboardController extends Controller
{
    use MerchantOrdersTrait;
    use CommonTrait;


    /**
     * Display a listing of the resource.
     *
     * @param $request request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dropshipdashboardUrl = $this->loginAndRedirect();

        return redirect()->away($dropshipdashboardUrl);
       
    }


}