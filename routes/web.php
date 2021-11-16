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
use Illuminate\Support\Facades\Route;

Route::get('login', 'ctl_login@showLoginForm')->name('web.login.showLoginForm'); //登录页
//1个IP 1分钟只能访问60次，超过报错429 Too Many Attempts
Route::post('login', 'ctl_login@login')->name('web.login.login')->middleware('throttle:60,1');
Route::get('logout', 'ctl_login@logout')->name('web.login.logout'); //退出
Route::get('register', 'ctl_member@showRegistrationForm')->name('web.member.showRegistrationForm');
Route::post('register', 'ctl_member@register')->name('web.member.register');
Route::get('password/reset', 'ctl_member@showLinkRequestForm')->name('web.password.request');
Route::post('password/email', 'ctl_member@sendResetLinkEmail')->name('web.password.email');
Route::get('password/reset/{token}', 'ctl_reset_pwd@showResetForm')->name('password.reset'); //該路由為laravel默認值改了會報錯
Route::post('password/reset', 'ctl_reset_pwd@reset')->name('web.password.update');


Route::match(['GET', 'POST'], '/', 'ctl_index@index')->name('web.index.index');
Route::match(['GET', 'POST'], 'about', 'ctl_about@index')->name('web.about.index');
Route::match(['GET', 'POST'], 'contact', 'ctl_contact@index')->name('web.contact.index');
Route::match(['GET', 'POST'], 'news', 'ctl_news@index')->name('web.news.index');
Route::match(['GET', 'POST'], 'news/detail', 'ctl_news@detail')->name('web.news.detail');
Route::match(['GET', 'POST'], 'products', 'ctl_products@index')->name('web.products.index');
Route::match(['GET', 'POST'], 'search', 'ctl_products@search')->name('web.products.search');
Route::match(['GET', 'POST'], 'products/detail', 'ctl_products@detail')->name('web.products.detail');
Route::match(['GET', 'POST'], 'display', 'ctl_display@index')->name('web.display.index');
Route::match(['GET', 'POST'], 'style', 'ctl_style@index')->name('web.style.index');

//需要登录才能访问的地址
Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => ['role:2']], function (){
        Route::match(['GET', 'POST'], 'change_pwd', 'ctl_change_pwd@edit')->name('web.change_pwd.edit');
    });
});
