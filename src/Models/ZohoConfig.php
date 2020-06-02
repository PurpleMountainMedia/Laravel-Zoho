<?php

namespace PurpleMountain\LaravelZoho\Models;

use Illuminate\Database\Eloquent\Model;

class ZohoConfig extends Model
{
    /**
     * Don't guard any of the attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types
     *
     * @var array
     */
    protected $casts = [
        'access_token_expires_at' => 'datetime'
    ];
}
