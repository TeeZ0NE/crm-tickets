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
Route::group(['middleware' => ['purify']], function () {
	Route::resource('/', 'IndexController')->only(['index']);
	Route::post('/bind-nicks', 'RealAdminController@bindNiks')->name('admins.bindNiks');
	Route::get('/nicks', 'RealAdminController@nicks')->name('admins.nicks');
	Route::resource('/admins', 'RealAdminController');
});
Route::view('/table', 'table');