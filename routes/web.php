<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//移动端
Route::get('/phone', function () {
    return view('phone');
});


//失物招领首页
Route::get('/', 'HomeController@index');

//寻物启事首页
Route::get('/found', function () {
    return view('found');
});

// 前端数据接口
Route::get('/api/getLostData/{param1}/{param2}/{param3}', 'Api_Data@callBackLostData');
Route::get('/api/getRecipientData/{param1}/{param2}/{param3}', 'Api_Data@callBackRecipientData');
Route::get('/api/search/{param1}/{param2}/{param3}/{param4}', 'Api_Data@callBackSearchData');
Route::get('/api/searchRecipient/{param1}/{param2}/{param3}/{param4}', 'Api_Data@callBackSearchRecipientData');

Route::get('/api/getLostData/{param1}', 'Api_Data@callBackLostDataAll');
Route::get('/api/getBase', 'Api_Data@getBase');

// 上传寻物启事
//Route::resource('/api/uploadLosts', 'FoundController');

// 搜索接口
//Route::post('/api/searchData', 'Api_Data@callBackSearchData');

// 后台管理页面
Route::group(['middleware' => 'auth', 'namespace' => 'Admin', 'prefix' => 'admin'], function() {
    Route::get('/', 'AdminController@index');
    Route::get('/taken', 'AdminController@taken');
    Route::get('/getBranchInfo', 'AdminController@getBranchInfo');

    // 后端RESTful接口
    Route::resource('/api/uploadLosts', 'LostController');

    // 超管RESTful接口
    Route::post('/api/superAcademy', 'superController@Academy');
    Route::get('/api/showAcademy/{param1}', 'superController@ShowAcademy');
    Route::post('/api/superPlace', 'superController@Place');
    Route::get('/api/showPlace/{param1}', 'superController@ShowPlace');
    Route::post('/api/superDeleteBranch', 'superController@BranchDelete');
    Route::post('/api/superBaseSave', 'superController@baseInfo');
    Route::get('/api/getBase', 'superController@getBase');

    Route::get('/logoutAndRegister', 'superController@logoutAndRegister');
    Route::get('/logout', 'superController@logout');

});
