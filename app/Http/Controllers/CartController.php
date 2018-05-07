<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    //
    public function cart(Request $request){
        $id=Auth::user()->id;
        //dd($id);
        DB::table('carts')->where('r_id','=',$id)->delete();
        $goods=$request->goodsList;
        $counts=$request->goodsCount;
       foreach($goods as $key=>$good){
               DB::table('carts')->insert([
                   'goodslist'=>$good,
                   'goodscount'=>$counts[$key],
                   'r_id'=>$id,
               ]);
       }
       return ["status"=>"true",
      "message"=> "添加成功"];
    }
    public function getcart(Request $request){
        $id=Auth::user()->id;
        //dd($id);
       $goodslists=DB::table('carts')->where('r_id','=',$id)->get();
       //dd($goodslists);

        $money=0;
        foreach($goodslists as $goodslist){
            $good=DB::table('goods')->where('id','=',$goodslist->goodslist)->first();
            $good->goods_id=$good->id;
            $good->amount=$goodslist->goodscount;
            $price=$good->amount*$good->goods_price;
            $money+=$price;
            $goods[]=$good;
        }
        $val['goods_list']=$goods;
        $val['totalCost']=$money;
        return $val;
    }
}
