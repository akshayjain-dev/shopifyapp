@extends('ssltd_dashboard.base')
@section('content')

<div class="container-fluid">
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
          <div class="card-body" style="background:#ebedef;">
            <div class="scrollspy-example1">

              <nav class="navbar navbar-light bg-light" id="navbar-example1" style="padding: .5rem 1rem;">
                <a href="{{ route('orders.index') }}"><button class="btn  btn-primary" type="button"> {{ __('Back to Order') }}</button></a>
                <h4># {{ $order->order_id }} <strong>(Total : {{ $order->currency }} {{ $order->total_amount }})</strong></h4>
                
              </nav>

            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-align-justify"></i>{{ __(' Order Information') }}
          </div>
          <div class="card-body">
            <ul class="list-group">
              <li class="list-group-item"><strong>{{ __('Order Date:') }}</strong> {{ \Carbon\Carbon::parse($order->purchase_date)->format('d-m-Y H:i:s')}}</li>
              <li class="list-group-item"><strong>{{ __('Order Status:') }} </strong> {{ ucfirst($order->order_status) }}
                
              </li>
              <li class="list-group-item"><strong>{{ __('Shopify Payment ID:') }} </strong> {{ $order->order_id }}</li>
              <li class="list-group-item"><strong>{{ __('Shopify GID:') }} </strong> {{ $order->g_id }}</li>
              <li class="list-group-item"><strong>{{ __('Payment Capture:') }} </strong> @if($order->order_type == 'sale') Automatic @else Manual @endif</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-align-justify"></i> {{ __('Account Information') }}
          </div>
          <div class="card-body">
            <ul class="list-group">
              <li class="list-group-item"><strong>{{ __('Customer Name:') }} </strong> @if(isset($billing_address['given_name'])){{ $billing_address['given_name'] }}@endif @if(isset($billing_address['family_name'])){{ $billing_address['family_name'] }}@endif</li>
              <li class="list-group-item"><strong>{{ __('Email:') }} </strong> {{ $order->email }}</li>
              <li class="list-group-item"><strong>{{ __('Phone No:') }} </strong> {{ $order->phone_no }}</li>
              <!-- <li class="list-group-item"><strong>Locale : </strong> {{ $order->locale }} ( {{ Config::get('constants.language_list.'.$order->locale) }} )</li> -->
            </ul>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-align-justify"></i> {{ __('Billing Address') }}
          </div>
          <div class="card-body">
            <ul class="list-group">
              @if(isset($billing_address['given_name']))<li class="list-group-item"><strong>{{ __('Given Name:') }} </strong> {{ $billing_address['given_name'] }}</li>@endif
              @if(isset($billing_address['first_name']))<li class="list-group-item"><strong>{{ __('First Name:') }} </strong> {{ $billing_address['first_name'] }}</li>@endif
              @if(isset($billing_address['last_name']))<li class="list-group-item"><strong>{{ __('Last Name:') }} </strong> {{ $billing_address['last_name'] }}</li>@endif
              @if(isset($billing_address['address1']))<li class="list-group-item"><strong>{{ __('Address 1:') }} </strong> {{ $billing_address['address1'] }}</li>@endif
              @if(isset($billing_address['address2']))<li class="list-group-item"><strong>{{ __('Address 2:') }} </strong> {{ $billing_address['address2'] }}</li>@endif
              @if(isset($billing_address['city']))<li class="list-group-item"><strong>{{ __('City:') }} </strong> {{ $billing_address['city'] }}</li>@endif
              @if(isset($billing_address['zip']))<li class="list-group-item"><strong>{{ __('Postal Code:') }} </strong> {{ $billing_address['zip'] }}</li>@endif
              @if(isset($billing_address['province']))<li class="list-group-item"><strong>{{ __('Province:') }} </strong> {{ $billing_address['province'] }}</li>@endif
              @if(isset($billing_address['country_code']))<li class="list-group-item"><strong>{{ __('Country:') }} </strong>{{ $billing_address['country_code'] }}</li>@endif
              @if(isset($billing_address['company']))<li class="list-group-item"><strong>{{ __('Company Name:') }} </strong>{{ $billing_address['company'] }}</li>@endif
            </ul>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-align-justify"></i> {{ __('Shipping Address') }}
          </div>
          <div class="card-body">
            <ul class="list-group">
              @if(isset($shipping_address['given_name']))<li class="list-group-item"><strong>{{ __('Given Name:') }} </strong> {{ $shipping_address['given_name'] }}</li>@endif
              @if(isset($shipping_address['first_name']))<li class="list-group-item"><strong>{{ __('First Name:') }} </strong> {{ $shipping_address['first_name'] }}</li>@endif
              @if(isset($shipping_address['last_name']))<li class="list-group-item"><strong>{{ __('Last Name:') }} </strong> {{ $shipping_address['last_name'] }}</li>@endif
              @if(isset($shipping_address['address1']))<li class="list-group-item"><strong>{{ __('Address 1:') }} </strong> {{ $shipping_address['address1'] }}</li>@endif
              @if(isset($shipping_address['address2']))<li class="list-group-item"><strong>{{ __('Address 2:') }} </strong> {{ $shipping_address['address2'] }}</li>@endif
              @if(isset($shipping_address['city']))<li class="list-group-item"><strong>{{ __('City:') }} </strong> {{ $shipping_address['city'] }}</li>@endif
              @if(isset($shipping_address['zip']))<li class="list-group-item"><strong>{{ __('Postal Code:') }} </strong> {{ $shipping_address['zip'] }}</li>@endif
              @if(isset($shipping_address['province']))<li class="list-group-item"><strong>{{ __('Province:') }} </strong> {{ $shipping_address['province'] }}</li>@endif
              @if(isset($shipping_address['country_code']))<li class="list-group-item"><strong>{{ __('Country:') }} </strong> {{ $shipping_address['country_code'] }}</li>@endif
              @if(isset($shipping_address['company']))<li class="list-group-item"><strong>{{ __('Company Name:') }} </strong> {{ $shipping_address['company'] }}</li>@endif
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection