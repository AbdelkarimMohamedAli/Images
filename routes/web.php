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

Route::post('/save', 'SystemController@savedata');

Auth::routes(['register' => false]);
// Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::middleware(['auth', 'super-access'])->group(function () {

    Route::get('/subscribes', 'HomeController@subscribes');

    Route::get('/subscribes/export/', 'SubscribeController@export');

   

});
Route::middleware(['auth', 'user-access'])->group(function () {
    //Route::get('/register', 'HomeController@register');
    Route::get('/register/pending/', 'HomeController@register_status_pending');
    Route::get('/register/export/', 'SubscribeController@exportregister');
    Route::get('/register/{slug}', 'HomeController@register_status')->name("status");
    Route::post('/registerstatus/{id}', 'HomeController@register_status_id')->name('registerstatus');
    Route::get('/pending/export/', 'SubscribeController@exportpending');
    Route::get('/approval/export/', 'SubscribeController@exportapproval');
    Route::get('/rejected/export/', 'SubscribeController@exportrejected');
});





