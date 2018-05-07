<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    //
    public function shops(){
        $shops=DB::table('admins')->get();
        //dd($shops);
   foreach ($shops as $shop){
            $shop->shop_rating='4.7';
            $shop->distance='637';
            $shop->estimate_time='30';
        }
        return $shops;
    }
    public function detail(Request $request){
        $id=$_GET['id'];
      $shops=DB::table('admins')->where('id','=',$id)->first();
            $shops->shop_rating='4.7';
            $shops->distance='637';
            $shops->estimate_time='30';
        $shops->evaluate=[
            [
                "user_id"=>12344,
                "username"=>"w******k",
                "user_img"=>"http://www.homework.com/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=> 1,
                "send_time"=> 30,
                "evaluate_details"=> "不怎么好吃"
            ]
        ];
        //$goods=DB::table('goods')->where('a_id','=',$id)->get();
        //菜品

        //var_dump($goods);die;
        //分类
        //$c_id=DB::table('goods')->where('a_id','=',$id)->value('c_id');
        //var_dump($c_id);die;
        $cates=DB::table('goodcates')->where('a_id','=',$id)->get();
        //var_dump($cates);die;
        //dd($cate);
        foreach ($cates as $cate){
           $cate->description=$cate->detail;
        $goods=DB::table('goods')->where('c_id','=',$cate->id)->get();
        foreach ($goods as $good){
            //$cate->goods_list['goods_id']=$good->id;
            $cate->goods_list[]=$good;
             $good->goods_id=$good->id;
          }

        }

        $shops->commodity=$cates;
        //var_dump($shops);exit;
        return json_encode($shops);
    }
}
