<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'email', 'is_subscribed'
    ];

    public $timestamps = false;
}
