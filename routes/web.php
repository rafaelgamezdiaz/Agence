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

Route::get('/', 'MainController@home');

Auth::routes();

Route::resource('comercial','ComercialController');
Route::post('comercial/relatorio','ComercialController@relatorio')->name('comercial.relatorio');
Route::post('comercial/grafico','ComercialController@grafico')->name('comercial.grafico');
Route::post('comercial/pizza','ComercialController@pizza')->name('comercial.pizza');

Route::get('/home', 'HomeController@index');
