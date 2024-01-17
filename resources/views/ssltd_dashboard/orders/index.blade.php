@extends('ssltd_dashboard.base')
@section('content')

<div class="overlay">
  <div id="loading-img"></div>
</div>
<div class="container-fluid">
  <div class="animated fadeIn">
    <div class="row">      
      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-align-justify"></i>{{ __('Orders') }}
          </div>
          <div class="card-body">
            <div class="alert alert-danger" style="display:none;">
              <div id='show-error'></div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group row">
                  <div class="col-md-12">
                    <div class="input-group">
                      <form method="POST" id="admin_search_form" class="input-group">
                        <input class="form-control" id="search_keyword" type="text" name="search_keyword" placeholder="{{ __('Search by Bill to Name') }}" autocomplete="email"><span class="input-group-append">
                          <button class="btn btn-primary" type="submit" id="admin_search_btn">{{ __('Search') }}</button></span>
                      </form>

                      <form method="POST"  action="{{ url('/ssltd/dropship-orders/') }}" id="admin_search_form" class="input-group">
                      @csrf  
                      @method('PATCH')
                        <input  id="dropship_orders" type="hidden" name="dropship_orders" ><span class="input-group-append">
                          <button class="btn btn-primary" type="submit" id="admin_dropship_btn">{{ __('Dropship Orders') }}</button></span>
                      </form>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-2">
                <label class="col-form-label records-count">
                  @if(count($orders) > 0) {{$count}} @else 0 @endif {{ __('records found') }}
                </label>
              </div>
              <div class="col-sm-3">
                <a class="btn btn-primary" href="{{ url('/ssltd/dropship-dashboard/') }}" id="dropship_dashboard">{{ __('Dropship Dashboard') }}</a>
              </div>
              <div class="col-sm-2" style="flex: 0 0 17.666667%;max-width: 17.666667%">
                @if(count($orders) > 0)
                  @if ($count > $orders->perPage() )
                    @include('ssltd_dashboard.orders.perPage', array('orders'=>$orders))
                  @endif
                @endif
              </div>
              <div class="col-sm-3" style="flex: 1 0 21%">
                @if ($orders->hasPages())
                  @include('ssltd_dashboard.orders.pagination', array('orders'=>$orders))
                @endif
              </div>
            </div>
            </div>
            <div class="collapse" id="filters" style="">
              <div class="card card-body">
                
                <form class="form-horizontal" action="{{ url(Config::get('constants.settings.admin_url').'/orders/search') }}" method="POST" enctype="multipart/form-data" name="filter_form" id="filter_form" role="search">
                  @csrf
                  
                    <div class="row">
                    <div class="col-sm-6">
                      <h5>{{ __('Order ID') }}</h5>
                      <div class="form-group row">
                        <div class="col-sm-9">
                          <input type="text" name="order_id" id="order_id" class="form-control" value="@if(isset($order_id)){{ $order_id }}@endif" placeholder="{{ __('Order ID') }}">
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <h5>{{ __('Status') }}</h5>
                      <div class="form-group row">
                        <div class="col-md-8">
                          <select class="form-control" id="status" name="status">
                            <option value="">Select Status</option>
                            <option value="Sending to Authorized" @if(request()->input('status') == 'Sending to Authorized') selected @endif>{{ __('Sending to Authorized') }}</option>
                            <option value="Sent for Settlement" @if(request()->input('status') == 'Sent for Settlement') selected @endif>{{ __('Sent for Settlement') }}</option>
                            <option value="Sent for Refund" @if(request()->input('status') == 'Sent for Refund') selected @endif>{{ __('Sent for Refund') }}</option>
                            <option value="Sent for Cancellation" @if(request()->input('status') == 'Sent for Cancellation') selected @endif>{{ __('Sent for Cancellation') }}</option>
                            <option value="authorized" @if(request()->input('status') == 'authorized') selected @endif>{{ __('Authorized') }}</option>
                            <option value="Captured" @if(request()->input('status') == 'Captured') selected @endif>{{ __('Captured') }}</option>
                            <option value="Cancelled" @if(request()->input('status') == 'Cancelled') selected @endif>{{ __('Cancelled') }}</option>
                            <option value="Refunded" @if(request()->input('status') == 'Refunded') selected @endif>{{ __('Refunded') }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <h5>{{ __('Purchase Date') }}</h5>
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="date_from"><strong>{{ __('from') }}</strong></label>
                        <div class="col-md-6">
                          <input class="form-control datepicker" id="date_from" value="{{ request()->input('date_from') }}" type="text" name="date_from" placeholder="{{ __('Choose from date') }}">
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <h5>&nbsp;</h5>
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="date_to"><strong>{{ __('to') }}</strong></label>
                        <div class="col-md-6">
                          <input class="form-control datepicker" id="date_to" type="text" name="date_to" value="{{ request()->input('date_to') }}" placeholder="{{ __('Choose to date') }}">
                        </div>
                      </div>
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-sm-9">
                    </div>
                    <div class="col-sm-3">
                      <button class="btn  btn-primary" type="button" data-toggle="collapse" data-target="#filters" aria-expanded="false" aria-controls="filters"> {{ __('Cancel') }}</button>
                      <button class="btn  btn-success filter-button" type="submit" data-toggle="collapse" data-target="#filters" aria-expanded="false" aria-controls="filters"> {{ __('Apply Filters') }}</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <table class="table table-responsive table-striped @if($count > 0) table-overflow @endif" width="100%">
              <thead>
                <tr>
                <th width="20%" class="order-sorting">{{ __('Select Order') }}
                    <span id="active_sorting" >
                      <i class="cil-arrow-top"></i>
                      <i style="display:none" class="cil-arrow-bottom"></i>
                    </span>
                  </th>

                  <th width="20%" class="order-sorting">{{ __('Order ID') }}
                    <span id="active_sorting" data-sort="order_id">
                      <i class="cil-arrow-top"></i>
                      <i style="display:none" class="cil-arrow-bottom"></i>
                    </span>
                  </th>
                  <th width="15%" class="order-sorting active">{{ __('Purchase Date') }}
                    <span id="active_sorting" data-sort="date">
                      <i class="cil-arrow-top"></i>
                      <i style="display:none" class="cil-arrow-bottom"></i>
                    </span>
                  </th>
                  <th width="15%" class="order-sorting">{{ __('Bill to Name') }}
                    <span id="active_sorting" data-sort="billname">
                      <i class="cil-arrow-top"></i>
                      <i style="display:none" class="cil-arrow-bottom"></i>
                    </span>
                  </th>
                  <th width="20%" class="order-sorting">{{ __('Email') }}
                    <span id="active_sorting" data-sort="email">
                      <i class="cil-arrow-top"></i>
                      <i style="display:none" class="cil-arrow-bottom"></i>
                    </span>
                  </th>
                  <th width="10%" class="order-sorting">{{ __('Grand Total') }}
                    <span id="active_sorting" data-sort="price">
                      <i class="cil-arrow-top"></i>
                      <i style="display:none" class="cil-arrow-bottom"></i>
                    </span>
                  </th>
                  <th width="10%" class="order-sorting">{{ __('Status') }}
                    <span id="active_sorting" data-sort="status">
                      <i class="cil-arrow-top"></i>
                      <i style="display:none" class="cil-arrow-bottom"></i>
                    </span>
                  </th>
                  <th width="10%">{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @if(count($orders) > 0)
                @foreach($orders as $order)
                <tr>
                <td width="20%">
                  <input type="checkbox" name="radio_order_id" id="radio_order_id_{{ $order->id }}" onclick="selectfordropship({{ $order->id }})" value="{{ $order->id }}" />

                </td>
                  <td width="20%">{{ $order->id }} </td>
                  <td width="15%">{{ \Carbon\Carbon::parse($order->purchase_date)->format('d-m-Y H:i:s')}}</td>
                  <td width="15%">@if(isset(json_decode($order->billing_address)->given_name)){{ json_decode($order->billing_address)->given_name }}@endif @if(isset(json_decode($order->billing_address)->family_name)) {{ json_decode($order->billing_address)->family_name }}@endif</td>
                  <td width="20%">{{ $order->email }}</td>
                  <td width="10%">{{ $order->currency }} {{ $order->total_amount }}</td>
                  <td width="10%">
                    <span class="{{ $order->order_status }}">
                      {{ ucfirst($order->order_status) }}
                    </span>
                  </td>
                  <td width="10%">
                    <a href="{{ url(Config::get('constants.settings.admin_url').'/orders/' . $order->id) }}" class="btn btn-block btn-primary" target="_blank">{{ __('View') }}</a>
                  </td>

                </tr>
                @endforeach
                @else
                <tr>
                  <td colspan='12' style="text-align:center;">{{ __('No orders found') }}</td>
                </tr>
                @endif
              </tbody>
            </table>
            <div class="row">
              <div class="col-sm-12">
                @if ($orders->hasPages())
                  @include('ssltd_dashboard.orders.pagination', array('orders'=>$orders))
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
