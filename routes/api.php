<?php
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
/*
|--------------------------------------------------------------------------
|  登录注册
|--------------------------------------------------------------------------
*/
Route ::namespace('Auth') -> group(function (){
    //获取手机验证码
    Route ::post('getcode','LoginController@getCode')
        //使用访问频率限制中间件,一分钟请求一次
        -> middleware('throttle:1')
    ;
    //用refreshtoken获取新的 token
    Route ::post('refreshtoken','LoginController@refreshToken');
    //用户登录
    Route ::post('login','LoginController@yqdLogin');
    //用户退出登录
    Route ::get('logout','LoginController@logout');
    //后端管理用户登录
    Route ::post('adminlogin','LoginController@adminLogin');
    //后端管理用户用refreshtoken获取新的 token
    Route ::post('adminrefreshtoken','LoginController@refreshToken');
    //后端管理用户退出登录
    Route ::get('adminlogout','LoginController@logout');
    //用户注册
    Route ::post('register','RegisterController@register')
        //使用访问频率限制中间件,一分钟请求五次
        -> middleware('throttle:5')
    ;
})
;
/*
|--------------------------------------------------------------------------
|  前台业务接口
|--------------------------------------------------------------------------
*/
Route :: middleware('auth:api')
    -> group(function (){
        //前台用户获取用户信息
        Route ::get('user','Index\IndexController@user');
    })
;
/*
|--------------------------------------------------------------------------
|  后台业务接口
|--------------------------------------------------------------------------
*/
Route ::middleware('auth:admins')
    -> prefix('admin') -> group(function (){
        //前台用户获取用户信息
        Route ::get('user','Admin\IndexController@user');
        Route ::get('get_roles','Admin\PermissionsController@getRoles');
        Route ::get('permission-all','Admin\PermissionsController@permissionAll');
        Route ::get('get_permissions','Admin\PermissionsController@getPermissions');
    })
;
