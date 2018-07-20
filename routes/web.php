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
use Illuminate\Support\Facades\Auth;
Route::group(['middleware' => ['purify']], function () {
//	Route::resource('/', 'Boss\IndexController')->only(['index']);
	Route::post('/bind-nicks', 'RealAdminController@bindNiks')->name('admins.bindNiks');
	Route::get('/nicks', 'RealAdminController@nicks')->name('admins.nicks');
	Route::resource('/admins', 'RealAdminController');
});

Route::view('/table', 'table');

# boss login
Route::group(['prefix' => 'boss', 'middleware' => ['purify']], function () {
	Route::get('/', 'Boss\Auth\LoginController@showLoginForm')->name('boss.login');
	Route::post('/', 'Boss\Auth\LoginController@login');
//	Route::post('logout', 'Boss\Auth\LoginController@logout')->name('boss.logout');

// Registration Routes...
//Route::get('register', 'Boss\Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Boss\Auth\RegisterController@register');

// Password Reset Routes...
	Route::get('password/reset', 'Boss\Auth\ForgotPasswordController@showLinkRequestForm')->name('boss.password.request');
	Route::post('password/email', 'Boss\Auth\ForgotPasswordController@sendResetLinkEmail')->name('boss.password.email');
	Route::get('password/reset/{token}', 'Boss\Auth\ResetPasswordController@showResetForm')->name('boss.password.reset');
	Route::post('password/reset', 'Boss\Auth\ResetPasswordController@reset');
	Route::get('/home', 'Boss\IndexController@index')->name('boss.home');
});
#sysadmin user
//Route::get('/', 'RealAdminController@index')->name('home');
Route::get('/', function(){
//	return redirect(route('home'));
//	if (Auth::check() && Auth::guard('boss')){

//		if(Auth::guard('boss')->check()) {echo 'boss '.Auth::guard('boss')->user()->name;}
//		if(Auth::guard()) {echo Auth::user()->name;}
//		echo route()->name;
//		return redirect(route('boss.home'));
//	}
	if (Auth::check()) {
		if (Auth::guard('boss')->check()) {
			return view('boss.home');
		}
		return view('home');
	}
	return view('auth.login');
});
Auth::routes();
Route::match(['get','post'], 'register', function () {
	return view('errors.403');
})->name('register');
Route::get('/home', 'HomeController@index')->name('home');
