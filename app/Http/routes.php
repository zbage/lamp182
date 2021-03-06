<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'Home\IndexController@getIndex');
// 前台搜索
Route::post('/','Home\IndexController@search');
// 验证码
Route::get('/code/{id}.jpg','Admin\LoginController@code') -> where('id','\d+');

// 后台登录
Route::get('/admin/login','Admin\LoginController@login');
// 后台处理登录信息
Route::post('/admin/dologin','Admin\LoginController@dologin');
// 后台用户退出
Route::get('/admin/logout', 'Admin\LoginController@logout');

Route::any('/home/upload','Admin\UsersetController@upload');

Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware' => 'admin.login'], function(){

    // 首页路由
    Route::resource('index', 'IndexController');
    // 前台用户管理模块
    Route::resource('user', 'MemberController');
    // 修改密码
    Route::resource('pass','PassController');
//    演员管理模块
    Route::resource('cast', 'CastController');
//    后台管理员信息修改（只能修改自己的）
    Route::resource('userset','UsersetController');
//    后台上传
    Route::any('upload','UsersetController@upload');

//    电影类型管理
    Route::resource('type', 'TypeController');
//    网站配置
    Route::controller('config', 'WebConfigController');
//    友情链接模块
    Route::resource('link','LinkController');
//    友情链接排序
    Route::get('link/order/{id}-{order}', 'LinkController@order');
//    后台电影路由
    Route::resource('film','FilmController');
//    影厅管理
    Route::resource('filmroom', 'FilmRoomController');
//    获取电影信息
    Route::get('films/{name}', 'FilmController@film');
//    后台管理员管理
    Route::resource('admins','AdminsController');
//    后台订单管理
    Route::resource('orders','OrdersController');
//    影厅硬件信息管理
    Route::resource('filmrooms','FilmRoomsController');
//    后台评论管理
    Route::resource('review','ReviewController');
});

Route::get('test', 'test@test');

// 前台登录
Route::get('login','Home\LoginController@login');
// 前台处理登录信息
Route::post('dologin','Home\LoginController@dologin');
// 忘记登录密码
Route::get('forget','Home\ForgetController@forget');
Route::post('doforget','Home\ForgetController@doforget');
Route::get('forget/phone_code','Home\ForgetController@phone_code');
// 设置新密码
Route::post('donewpass','Home\ForgetController@donewpass');

// 前台注册用户
Route::get('reg','Home\RegController@reg');
// 前台处理注册信息
Route::post('doreg','Home\RegController@doreg');
// 手机注册验证码
Route::get('reg/phone_code','Home\RegController@phone_code');
// 邮箱注册
Route::post('reg/doemail','Home\RegController@doemail');
// 邮箱激活
Route::get('reg/jihuo','Home\RegController@jihuo');

Route::group(['namespace' => 'Home'],function (){
    Route::controller('/order', 'OrderController');
    // 前台电影详情
    Route::get('filmdetails/{id}','FilmDetailsController@index');
//    电影评论
    Route::post('comment','FilmDetailsController@comment');
//    影厅弹层
    Route::post('movie','FilmDetailsController@movie');
//    电影类型 电影搜索
    Route::controller('type','TypeController');
});

Route::group(['namespace' => 'Home', 'middleware' => 'home.login'],function (){
//    电影订单
    Route::controller('/order', 'OrderController');
// 个人中心页面
    Route::get('/personage/basic','PersonageController@getIndex');
    Route::post('/personage/basic','PersonageController@getIndex');
// 个人修改基本信息
    Route::post('/personage/save','PersonageController@postSave');
// 个人安全设置
    Route::post('/personage/secure','PersonageController@postSecure');
// 个人余额页面
    Route::post('/personage/money','PersonageController@postMoney');
// 个人评论过的电影
    Route::post('/personage/review','PersonageController@postReview');
// 个人订单页面
    Route::post('/personage/consume','PersonageController@postConsume');
// 用户消费单页
    Route::get('orderlist','ListController@Orderlist');
// 用户评论过的电影页
    Route::get('reviewlist','ListController@Reviewlist');
// 修改密码
    Route::get('updatepassword','SecureController@updatePassword');
// 验证密码ajax
    Route::post('ajaxPWD','SecureController@passwordAjax');
// 发送短信改密码
    Route::get('phoneAjax','SecureController@phoneAjax');
// 进行修改密码
    Route::post('updateValidate','SecureController@updateValidate');

// 更换邮箱
    //Route::get();
// 更换手机号
    //Route::get();
// 退出登录
    Route::get('logout','LoginController@logout');
});
