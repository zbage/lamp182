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

Route::get('/', function () {
    return view('welcome');
});
// 验证码
Route::get('/code/{id}.jpg','Admin\LoginController@code') -> where('id','\d+');


// 后台登录
Route::get('/admin/login','Admin\LoginController@login');
// 后台处理登录信息
Route::post('/admin/dologin','Admin\LoginController@dologin');
// 后台用户退出
Route::get('/admin/logout', 'Admin\LoginController@logout');



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
//    后台头像上传
    Route::any('upload','UsersetController@upload');
//    后台电影路由
    Route::get('film/create','FilmController@create');
    Route::get('film/show','FilmController@show');
    Route::post('film/store','FilmController@store');
    Route::get('film/edit','FilmController@edit');
    Route::post('film/update','FilmController@update');
    Route::get('film/delete','FilmController@delete');
});
