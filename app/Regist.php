<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Regist extends Model
{
    //
    protected $fillable = [
        'username', 'tel', 'password',
    ];
}
