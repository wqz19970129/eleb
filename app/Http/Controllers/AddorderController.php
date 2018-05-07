<?php

namespace App\Http\Controllers;

use App\Addoder;
use App\Email;
use App\Order_sms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddorderController extends Controller
{
    //
    public function add(Request $request)
    {
        $id= Auth::user()->id;
        //查购物车表goodslist为goods_id
        $carts = DB::table('carts')->where('r_id', '=', $id)->get();
        //dd($carts);
        foreach ($carts as $cart) {
            $good = DB::table('goods')->where('id', '=', $cart->goodslist)->first();
            $address_id = $request->address_id;
            $address = DB::table('addresses')->where('id', '=', $address_id)->first();
            $admin = DB::table('admins')->where('id', '=', $good->a_id)->first();
        }
        $order_code = mt_rand(000000, 100000);
        DB::transaction(function ()use ($order_code,$admin,$address,$carts){
            $addorder = Addoder::create([
                'order_code' => $order_code,
                'order_birth_time' => date('Y-m-d H:i:s', time()),
                'shop_id' => $admin->id,
                'shop_name' => $admin->shop_name,
                'shop_img' => $admin->shop_img,
                'name' => $address->name,
                'tel' => $address->tel,
                'provence' => $address->provence,
                'city' => $address->city,
                'area' => $address->area,
                'detail_address' => $address->detail_address,
                'order_status' => '代付款',
                'r_id' =>Auth::user()->id
            ]);
            foreach ($carts as $cart) {
                $good = DB::table('goods')->where('id', '=', $cart->goodslist)->first();

                $good->amount = $cart->goodscount;

                DB::table('ordergs')->insert(
                    [
                        'order_id' => $addorder->id,
                        'goods_id' => $good->id,
                        'goods_name' => $good->goods_name,
                        'goods_img' => $good->goods_img,
                        'goods_price' => $good->goods_price,
                        'amount' => $good->amount,
                        'a_id'=>$admin->id,
                        'created_at'=>date('Y-m-d H:i:s',time()),
                    ]
                );
            }
        });
        $name=$admin->shop_name;
        $tel=$address->tel;
        $add=DB::table('addoders')->where('order_code',$order_code)->value('id');
        //dd($tel);
        Email::email($admin->shop_name);
        Order_sms::sendSms($name,$tel,$order_code);
         return ['status' => 'true', 'message' => '添加成功', 'order_id' =>$add];
}
   public function order(Request $request){
        $id=$request->id;
        $order=DB::table('addoders')->where('id','=',$id)->first();
       $goods=[];
       $ordergs=DB::table('ordergs')->where('order_id','=',$id)->get();
       $order->goods_list=$ordergs;
       $order->order_address=$order->detail_address;
       $id=Auth::user()->id;
       //dd($id);
       $goodslists=DB::table('carts')->where('r_id','=',$id)->get();
       //dd($goodslists);
       $money=0;
       foreach($goodslists as $goodslist){
           $good=DB::table('goods')->where('id','=',$goodslist->goodslist)->first();
           $good->amount=$goodslist->goodscount;
           $price=$good->amount*$good->goods_price;
           $money+=$price;
           $goods[]=$good;
       }
       $order->order_price=$money;
      return json_encode($order);
 }
 public function orderlist(Request $request){
     $id=Auth::user()->id;
     $orders=DB::table('addoders')->where('r_id',$id)->get();
     //dd($orders);
     foreach ($orders as $order){
         $goodslists=DB::table('carts')->where('r_id','=',$id)->get();

         $ordergs=DB::table('ordergs')->where('order_id','=',$order->id)->get();
         //dd($ordergs);
         $order->goods_list=$ordergs;

         $money=0;
         $goods=[];
         foreach($goodslists as $goodslist){
             $good=DB::table('goods')->where('id','=',$goodslist->goodslist)->first();
             $good->amount=$goodslist->goodscount;
             $price=$good->amount*$good->goods_price;
             $money+=$price;
             $goods[]=$good;
         }
         $order->order_price=$money;
         $order->order_address=$order->detail_address;
     }
     return $orders;
 }
}
