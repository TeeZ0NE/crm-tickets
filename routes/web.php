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
	Route::get('/home', 'Boss\IndexController')->name('boss.home');
	Route::put('ticket/{id}', 'Boss\TicketController@update')->name('boss.ticket.update');
	Route::get('/nicks', 'Boss\RealAdminController@nicks')->name('admins.nicks');
	Route::post('/bind-nicks', 'Boss\RealAdminController@bindNiks')->name('admins.bindNiks');
	Route::put('/deactivate/{user_id}','Boss\RealAdminController@deactivate')->name('admin.deactivate');
	Route::group(['prefix'=>'admins'], function(){
		Route::get('/statistics', 'Boss\StatisticController@index')->name('admins.statistics');
		Route::get('/statistics-submonth/', 'Boss\StatisticController@getStatisticsSubMonth')->name('admins.statistics_subMonths');
	});
	Route::group(['prefix'=>'services'], function(){
		Route::get('/','Boss\ServicesController@index')->name('services.index');
		Route::put('/{service}','Boss\ServicesController@update')->name('services.update');
		Route::delete('/{service}','Boss\ServicesController@destroy')->name('services.destroy');
		Route::get('create','Boss\ServicesController@serviceCreate')->name('services.new');
		Route::post('create','Boss\ServicesController@create')->name('services.create');
	});
	Route::get('/logs','Boss\LogController')->name('logs');
});
Route::resource('/boss/admins', 'Boss\RealAdminController')->middleware('purify');
Route::resource('/boss/deadline','Boss\DeadlineController')->middleware('purify');
Route::get('/', function(){
	if (Auth::check()) {
		if (Auth::guard('boss')->check()) {
			return redirect(route('boss.home'));
		}
		return redirect(route('home'));
	}
	return view('auth.login');
});
Auth::routes();
Route::match(['get','post'], 'register', function () {
	return view('errors.403');
})->name('register');
Route::group(['middleware' => ['purify','auth']], function () {
	Route::get('/home', 'Admin\HomeController@index')->name('home');
	Route::get('/statistic','Admin\HomeController@statistic')->name('admins.statistic');
	Route::post('admin/{id}/assign-ticket/{ticket_id}', 'Admin\TicketsController@assignTicket')->name('admin.assign-ticket');
//	Route::get('admin/{id}/assign-ticket/{ticket_id}',function(){
//		return redirect(404);
//	});
});

