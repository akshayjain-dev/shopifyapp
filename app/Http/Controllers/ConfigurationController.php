<?php

namespace App\Http\Controllers;
use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
        $configuration = auth()->user()->configuration;
        return view('configuration.index', compact('configuration'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        if ($user->configuration) {
            $user->configuration->update([
                'open_cart_email' => $request->input('open_cart_email'),
                'open_cart_password' => $request->input('open_cart_password'),
                'shopify_token' => $request->input('shopify_token'),
                'webhook_url' => $request->input('webhook_url'),
            ]);
        } else {
            Configuration::create([
                'user_id' => $user->id,
                'open_cart_email' => $request->input('open_cart_email'),
                'open_cart_password' => $request->input('open_cart_password'),
                'shopify_token' => $request->input('shopify_token'),
                'webhook_url' => $request->input('webhook_url'),
            ]);
        }
        return redirect()->route('configuration.index')->with('success', 'Configuration updated successfully');
    }
}
