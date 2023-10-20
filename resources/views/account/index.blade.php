@extends('layouts.app')

@section('content')
<div class="container">
    <h5>My Account</h5>
    <p class="mt-4">Name: {{ $user->name }}</p>
    <p class="mt-2">Email: {{ $user->email }}</p>
</div>
@endsection