<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register','RegistController@reg');
Route::post('/login','RegistController@login');
Route::post('/forget','RegistController@forget');
Route::post('/change','RegistController@change');
Route::post('/address','AddressController@address');
Route::get('/addresslist','AddressController@alist');
Route::post('/updateaddress','AddressController@update');
Route::get('/edit','AddressController@edit');
Route::post('/addcart','CartController@cart');
Route::get('/getcart','CartController@getcart');
Route::get('/sms','SmsController@index');
//生成
Route::post('/addorder','AddorderController@add');
Route::get('/order','AddorderController@order');
Route::get('/orderlist','AddorderController@orderlist');
