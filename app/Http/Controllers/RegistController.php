<?php

namespace App\Http\Controllers;

use App\Regist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class RegistController extends Controller
{
    //
    public function reg(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:regists|max:255',
            'tel' => 'required|unique:regists',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['status'=>'false','message'=>$errors->first()];
 /*           return '{
      "status": "false",
      "message": "注册失败"
    }';*/
        }
        //验证码
        $code=Redis::get('code_'.$request->tel);
        if($code != $request->sms){
           return '{
      "status": "false",
      "message": "验证码发送失败,请等候"
    }';
        }else{
            $this->validate($request,
                [
                    'username'=>'required',
                    'tel'=>'required',
                    'password'=>'required',
                ],
                [
                    'username.required'=>'用户名不为空',
                    'tel.required'=>'电话不为空',
                    'password.required'=>'密码不为空',
                ]);

            Regist::create([
                'username'=>$request->username,
                'tel'=>$request->tel,
                'password'=>bcrypt($request->password),
            ]);
            echo '{
      "status": "true",
      "message": "注册成功"
    }';
        }

    }
    public function login(Request $request){
        $this->validate($request,
            [
                'name'=>'required',
                'password'=>'required',
            ],[
                'name.required'=>'名字不为空',
                'password.required'=>'密码不为空',
            ]);
        if(Auth::attempt(['username'=>$request->name,'password'=>$request->password,'status'=>0])){
          return ["status"=>"true",
        "message"=>"登录成功",
        "user_id"=>Auth::user()->id,
        "username"=>Auth::user()->username];
        }else{
            echo '{
        "status":"false",
        "message":"登录失败,密码不正确",
    }';
        }
    }
     //忘记密码
    public function forget(Request $request){
        $validator = Validator::make($request->all(), [
            'tel' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()){
            $errors = $validator->errors();
            return ['status'=>'false','message'=>$errors->first()];
        }

        $code=Redis::get('code_'.$request->tel);
        //dd($request->sms);
        if($code == $request->sms){
            $tel=$request->tel;
            DB::table('regists')->where('tel','=',$tel)->update(['password'=>bcrypt($request->password)]);
            return ['status'=>'true','message'=>'添加成功'];
        }else{
            return ['status'=>'false','message'=>'修改失败'];
        }
    }
    public function change(Request $request)
    {
        $id = Auth::user()->id;
      if(Hash::check($request->oldPassword,Auth::user()->password)){
          $new=bcrypt($request->newPassword);
          DB::table('regists')->where('id', '=', $id)->update(['password' => $new]);
          return ['status' => 'true', 'message' => '添加成功'];
      }else{
          return ['status' => 'false', 'message' => '修改失败'];
      }
    }

}
