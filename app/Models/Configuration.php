<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 
        'open_cart_email',
        'open_cart_password',
        'shopify_token',
        'webhook_url',
    ];
}
