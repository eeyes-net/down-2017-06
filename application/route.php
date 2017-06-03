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

Route::group('admin', function () {
    Route::get('/', 'admin/Index/index');
    Route::get('refreshFiles', 'admin/Index/refreshFiles');
    Route::get('getFiles', 'admin/Index/getFiles');
    Route::put('updateFile/:id', 'admin/Index/updateFile');
    Route::post('createItem', 'admin/Index/createItem');
    Route::put('updateItem/:id', 'admin/Index/updateItem');
    Route::get('getList', 'admin/Index/getList');
    Route::put('sortList', 'admin/Index/sortList');
});
