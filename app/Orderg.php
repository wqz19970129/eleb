<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orderg extends Model
{
    //protected $guarded=[];
    protected $fillable = [
        'order_id', 'goods_id', 'amount','goods_price','goods_name','goods_img','a_id'
    ];
}
