<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        return view('account.index', [
            'user' => $user,
        ]);
    }
}
