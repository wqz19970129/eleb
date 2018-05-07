<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    //
    public function address(Request $request){
        $validator=Validator::make($request->all(),[
                "name"=>"required|max:10",
                "tel"=>"required|regex:/^1[3458][0-9]\d{8}$/",
                "provence"=>"required",
                "city"=>"required",
                "area"=>"required",
                "detail_address"=>"required",
            ]
            ,
            ['name.required'=>'不为空']);
        if($validator->fails()){
            $errors = $validator->errors();
            return ['status'=>'false','message'=>$errors->first()];
        }
        $id=Auth::user()->id;
        //var_dump($id);die;
        DB::table('addresses')->insert([
            'name'=>$request->name,
            'tel'=>$request->tel,
            'provence'=>$request->provence,
            'city'=>$request->city,
            'area'=>$request->area,
            'detail_address'=>$request->detail_address,
            'r_id'=>$id
        ]);
        echo '{status": "true",
             "message": "添加成功"}';
    }
     public function alist(){
        $id=Auth::user()->id;
        $alist=DB::table('addresses')->where('r_id',$id)->get();
        //dd($alist);
        return $alist;
     }
     public function update(Request $request){
         //$id=Auth::user()->id;
         //dd($id);
          $validator=Validator::make($request->all(),[
              "name"=>"required|max:10",
              "tel"=>"required|regex:/^1[3458][0-9]\d{8}$/",
              "provence"=>"required",
              "city"=>"required",
              "area"=>"required",
              "detail_address"=>"required",
          ]
          ,
              ['name.required'=>'不为空']);
          if($validator->fails()){
              $errors = $validator->errors();
              return ['status'=>'false','message'=>$errors->first()];
          }
          DB::table('addresses')->where('id','=',$request->id)->update(
              [
                  'name'=>$request->name,
                  'tel'=>$request->tel,
                  'provence'=>$request->provence,
                  'city'=>$request->city,
                  'area'=>$request->area,
                  'detail_address'=>$request->detail_address,
                 // 'r_id'=>$id,
                  ]
          );
         return ['status'=>'true','message'=>'修改成功'];
     }
     public function edit(Request $request){
         $row=DB::table('addresses')->where('id','=',$request->id)->first();
         return  json_encode($row);
     }

}
