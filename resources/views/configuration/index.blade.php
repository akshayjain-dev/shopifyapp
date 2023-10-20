@extends('layouts.app')

@section('content')
<div class="container">
    <h5>Configuration Settings</h5>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
</div>
<div class="container mt-4">
    <form method="POST" action="{{ route('configuration.update') }}">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="open_cart_email">Open Cart Email</label>
            <input type="text" name="open_cart_email" id="open_cart_email" value="{{ $configuration->open_cart_email ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="open_cart_password">Open Cart Password</label>
            <input type="password" name="open_cart_password" id="open_cart_password" value="{{ $configuration->open_cart_password ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="shopify_token">Shopify Token</label>
            <input type="text" name="shopify_token" id="shopify_token" value="{{ $configuration->shopify_token ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="webhook_url">Webhook URL</label>
            <input type="text" name="webhook_url" id="webhook_url" value="{{ $configuration->webhook_url ?? '' }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Save</button>
    </form>
</div>
@endsection