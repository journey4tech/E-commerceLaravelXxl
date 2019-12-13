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

Route::get('/', function () {
    return view('welcome');
});




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



Route::get('/admin','AdminController@login')->name('admin.login');


Route::group(['middleware'=>['auth','admin']], function () {

    Route::get('/admin/dashboard','AdminController@dashboard')->name('admin.dashbord');
    Route::get('/admin/setting','AdminController@setting')->name('admin.setting');
    Route::get('/admin/check-pwd','AdminController@checkPasd')->name('admin.checkPasd');
    Route::post('/admin/updatepassword','AdminController@updatePassword')->name('admin.updatePassword');

    // Categories Routes (Admin)
    Route::match(['get','post'],'/admin/add-category','CategoryController@addCategory');
    Route::post('/admin/save-category','CategoryController@saveCategory');
    Route::match(['get','post'],'/admin/edit-category/{id}','CategoryController@editCategory');
    Route::match(['get','post'],'/admin/delete-category/{id}','CategoryController@deleteCategory');
    Route::get('/admin/view-categories','CategoryController@viewCategories');

});
