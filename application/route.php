<?php

use think\Route;

Route::get('/', 'index/Index/index');
Route::get('list', 'index/Index/getDownList');
Route::get('down/:id/:type', 'index/Index/down');
Route::post('issue', 'index/Index/saveIssue');
Route::get('comment','index/Index/getComment');
Route::post('comment','index/Index/saveComment');

Route::group('cas', function () {
    Route::get('login', 'auth/CasLogin/login');
    Route::get('logout', 'auth/CasLogin/logout');
    Route::get('user','auth/CasLogin/getUser');
});

Route::group('oauth', function () {
    Route::get('login','auth/OAuthLogin/login');
    Route::get('logout','auth/OAuthLogin/logout');
    Route::get('user','auth/OAuthLogin/getUser');
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
    Route::get('comment','admin/Index/getComment');
    Route::post('comment','admin/Index/saveComment');
    Route::delete('comment/:id','admin/Index/deleteComment');
    Route::delete('comments/:user_id','admin/Index/deleteCommentsByUserID');
});
