<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addoder extends Model
{
    //
    protected $fillable = [
        'name', 'tel', 'provence','city','area','detail_address','order_code','order_birth_time','shop_id','shop_name','shop_img','order_status','r_id'
    ];
}
