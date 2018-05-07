<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    public static function email($name)
    {
        ////测试发送邮件
        \Illuminate\Support\Facades\Mail::send(
            'mail',//邮件视图模板
            ['name' => $name],
            function ($message){
                $message->to('1280356689@qq.com')->subject('订单确认');
            }

        );
        return '邮件发送成功';
   /*     ////测试发送邮件
        \Illuminate\Support\Facades\Mail::send(
            'mail',//邮件视图模板
            ['name' => $name],
            function ($message)use($email)  {
                $message->to($email)->subject('订单确认');
            }

        );
        return '邮件发送成功';*/
    }
}
