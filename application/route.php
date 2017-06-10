<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


use think\Route;

Route::get('/', 'index/Index/index');
Route::post('issue', 'index/Index/saveIssue');

Route::group('admin', function () {
    Route::get('/', 'admin/Index/index');
    Route::get('auth', 'admin/Index/isLogin');
    Route::post('auth/login', 'admin/Index/login');
    Route::post('auth/logout', 'admin/Index/logout');
    Route::get('files/refresh', 'admin/Index/refreshFiles');
    Route::get('files', 'admin/Index/getFiles');
    Route::put('file/:id', 'admin/Index/updateFile');
    Route::get('list', 'admin/Index/getList');
    Route::put('list', 'admin/Index/updateList');
    Route::post('item', 'admin/Index/createItem');
    Route::put('item/:id', 'admin/Index/updateItem');
    Route::delete('item/:id', 'admin/Index/deleteItem');
    Route::get('icons', 'admin/Index/getIcons');
    Route::post('icons', 'admin/Index/uploadIcon');
    Route::get('issues', 'admin/Index/getIssues');
});
