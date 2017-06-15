<?php

use think\Route;

Route::get('/', 'index/Index/index');
Route::get('list', 'index/Index/getDownList');
Route::get('down/:id/:type', 'index/Index/down');
Route::post('issue', 'index/Index/saveIssue');

Route::group('cas', function () {
    Route::get('login', 'auth/CasLogin/login');
    Route::get('logout', 'auth/CasLogin/logout');
});

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
    Route::get('stats/date', 'admin/Index/getStatsByDate');
    Route::get('stats/file', 'admin/Index/getStatsByFile');
});
