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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return 'hello word';
});
/*---------- 后台 ----------*/
Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    /*---------- 登录 ----------*/
    Route::get('/', 'IndexController@index')->name('g_index');
    Route::post('/login', 'IndexController@login')->name('g_login');
    Route::get('/logout', 'IndexController@logout')->name('g_logout');
//    Route::middleware('CheckLogin:admin_user,g_index')->group(function (){
    /*---------- 【首页】 ----------*/
    Route::get('/home', 'HomeController@index')->name('g_home');
    Route::get('/home_index', 'HomeController@home')->name('g_home_index');
    /*---------- 【系统管理】 ----------*/
    //数据备份/还原
    Route::get('/data_lists', 'DataController@lists')->name('g_data_lists');
    Route::any('/data_import', 'DataController@import_data')->name('g_data_import');
    Route::any('/data_back', 'DataController@back_data')->name('g_data_back');
    /*---------- 【后台权限管理】 ----------*/
    //管理员管理
    Route::get('/admin_lists', 'AdminController@lists')->name('g_admin_lists');
    Route::any('/admin_add', 'AdminController@add')->name('g_admin_add');
    Route::any('/admin_edit', 'AdminController@edit')->name('g_admin_edit');
    Route::any('/admin_del', 'AdminController@del')->name('g_admin_del');
    //角色管理
    Route::get('/role_lists', 'RoleController@lists')->name('g_role_lists');
    Route::any('/role_add', 'RoleController@add')->name('g_role_add');
    Route::any('/role_edit', 'RoleController@edit')->name('g_role_edit');
    Route::any('/role_del', 'RoleController@del')->name('g_role_del');
    Route::any('/role_give_access', 'RoleController@give_access')->name('g_role_give_access');
    //节点管理
    Route::get('/node_lists', 'NodeController@lists')->name('g_node_lists');
    Route::any('/node_add', 'NodeController@add')->name('g_node_add');
    Route::any('/node_edit', 'NodeController@edit')->name('g_node_edit');
    Route::any('/node_del', 'NodeController@del')->name('g_node_del');
    //新闻管理
    Route::get('/new_lists','NewController@lists')->name('g_new_lists');
    Route::get('/new_add','NewController@add')->name('g_new_add');
    Route::any('/new_create','NewController@create')->name('g_new_create');
    Route::any('/new_uploadImg', 'NewController@upload')->name('uploadImg');


//    });

});
