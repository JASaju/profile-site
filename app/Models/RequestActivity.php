<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestActivity extends Model
{
    protected $fillable = [
        'ip_address',
        'method',
        'path',
        'status_code',
        'country',
        'region',
        'city',
        'latitude',
        'longitude',
    ];
}
