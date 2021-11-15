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

Route::get('login', 'ctl_login@showLoginForm')->name('admin.login.showLoginForm'); //登录页
//1个IP 1分钟只能访问60次，超过报错429 Too Many Attempts
Route::post('login', 'ctl_login@login')->name('admin.login.login')->middleware('throttle:60,1');
Route::get('logout', 'ctl_login@logout')->name('admin.login.logout'); //退出
Route::get('get_csrf_token', 'ctl_common@get_csrf_token')->name('admin.common.get_csrf_token'); //后台接口测试用

Route::group(['middleware' => ['auth:admin']], function (){ //中间件执行顺序由外层而内
    //主入口
    //Route::match(['GET', 'POST'], 'index', 'ctl_index@index')->name('admin.index.index');
    Route::match(['GET', 'POST'], '/', 'ctl_index@index')->name('admin.index.index');
    Route::match(['GET', 'POST'], 'admin_user/editpwd', 'ctl_admin_user@editpwd')->name('admin.admin_user.editpwd');
    Route::match(['GET', 'POST'], 'upload_chunked', 'ctl_upload@upload_chunked')->name('admin.upload.upload_chunked');
    Route::match(['GET', 'POST'], 'upload', 'ctl_upload@upload')->name('admin.upload.upload');
    Route::match(['GET', 'POST'], 'download', 'ctl_upload@download')->name('admin.upload.download');
    Route::match(['GET', 'POST'], 'translate', 'ctl_common@translate')->name('admin.common.translate');
    Route::match(['GET', 'POST'], 'wk_send', 'ctl_common@wk_send')->name('admin.common.wk_send');
    Route::match(['GET', 'POST'], 'ajax_get_area', 'ctl_common@ajax_get_area')->name('admin.common.ajax_get_area');
    Route::match(['GET', 'POST'], 'ajax_get_goods_cat', 'ctl_common@ajax_get_goods_cat')->name('admin.common.ajax_get_goods_cat');
    Route::match(['GET', 'POST'], 'ajax_get_navigation', 'ctl_common@ajax_get_navigation')->name('admin.common.ajax_get_navigation');

    Route::group(['middleware' => ['permission:admin']], function (){
        Route::match(['GET', 'POST'], 'admin_user', 'ctl_admin_user@index')->name('admin.admin_user.index');
        Route::match(['GET', 'POST'], 'admin_user/add', 'ctl_admin_user@add')->name('admin.admin_user.add');
        Route::match(['GET', 'POST'], 'admin_user/edit', 'ctl_admin_user@edit')->name('admin.admin_user.edit');
        Route::match(['GET', 'POST'], 'admin_user/delete', 'ctl_admin_user@delete')->name('admin.admin_user.delete');
        Route::match(['GET', 'POST'], 'admin_user/enable', 'ctl_admin_user@enable')->name('admin.admin_user.enable');
        Route::match(['GET', 'POST'], 'admin_user/disable', 'ctl_admin_user@disable')->name('admin.admin_user.disable');
        Route::match(['GET', 'POST'], 'admin_user/export_list', 'ctl_admin_user@export_list')->name('admin.admin_user.export_list');
        Route::match(['GET', 'POST'], 'admin_user/purview', 'ctl_admin_user@purview')->name('admin.admin_user.purview');
        Route::match(['GET', 'POST'], 'admin_user/del_purview', 'ctl_admin_user@del_purview')->name('admin.admin_user.del_purview');
        Route::match(['GET', 'POST'], 'role', 'ctl_role@index')->name('admin.role.index');
        Route::match(['GET', 'POST'], 'role/add', 'ctl_role@add')->name('admin.role.add');
        Route::match(['GET', 'POST'], 'role/edit', 'ctl_role@edit')->name('admin.role.edit');
        Route::match(['GET', 'POST'], 'role/delete', 'ctl_role@delete')->name('admin.role.delete');
        Route::match(['GET', 'POST'], 'role/export_list', 'ctl_role@export_list')->name('admin.role.export_list');
        Route::match(['GET', 'POST'], 'permission', 'ctl_permission@index')->name('admin.permission.index');
        Route::match(['GET', 'POST'], 'permission/add', 'ctl_permission@add')->name('admin.permission.add');
        Route::match(['GET', 'POST'], 'permission/edit', 'ctl_permission@edit')->name('admin.permission.edit');
        Route::match(['GET', 'POST'], 'permission/delete', 'ctl_permission@delete')->name('admin.permission.delete');
        Route::match(['GET', 'POST'], 'permission/export_list', 'ctl_permission@export_list')->name('admin.permission.export_list');
        Route::match(['GET', 'POST'], 'permission_group', 'ctl_permission_group@index')->name('admin.permission_group.index');
        Route::match(['GET', 'POST'], 'permission_group/add', 'ctl_permission_group@add')->name('admin.permission_group.add');
        Route::match(['GET', 'POST'], 'permission_group/edit', 'ctl_permission_group@edit')->name('admin.permission_group.edit');
        Route::match(['GET', 'POST'], 'permission_group/delete', 'ctl_permission_group@delete')->name('admin.permission_group.delete');
        Route::match(['GET', 'POST'], 'permission_group/export_list', 'ctl_permission_group@export_list')->name('admin.permission_group.export_list');
        Route::match(['GET', 'POST'], 'navigation', 'ctl_navigation@index')->name('admin.navigation.index');
        Route::match(['GET', 'POST'], 'navigation/add', 'ctl_navigation@add')->name('admin.navigation.add');
        Route::match(['GET', 'POST'], 'navigation/edit', 'ctl_navigation@edit')->name('admin.navigation.edit');
        Route::match(['GET', 'POST'], 'navigation/delete', 'ctl_navigation@delete')->name('admin.navigation.delete');
        Route::match(['GET', 'POST'], 'admin_user_login', 'ctl_admin_user_login@index')->name('admin.admin_user_login.index');
        Route::match(['GET', 'POST'], 'admin_user_login/delete', 'ctl_admin_user_login@delete')->name('admin.admin_user_login.delete');
        Route::match(['GET', 'POST'], 'admin_user_login/export_list', 'ctl_admin_user_login@export_list')->name('admin.admin_user_login.export_list');
        Route::match(['GET', 'POST'], 'admin_user_oplog', 'ctl_admin_user_oplog@index')->name('admin.admin_user_oplog.index');
        Route::match(['GET', 'POST'], 'admin_user_oplog/delete', 'ctl_admin_user_oplog@delete')->name('admin.admin_user_oplog.delete');
        Route::match(['GET', 'POST'], 'admin_user_oplog/export_list', 'ctl_admin_user_oplog@export_list')->name('admin.admin_user_oplog.export_list');
        Route::match(['GET', 'POST'], 'member_login', 'ctl_member_login@index')->name('admin.member_login.index');
        Route::match(['GET', 'POST'], 'member_login/delete', 'ctl_member_login@delete')->name('admin.member_login.delete');
        Route::match(['GET', 'POST'], 'member_login/export_list', 'ctl_member_login@export_list')->name('admin.member_login.export_list');
        Route::match(['GET', 'POST'], 'api_req_log', 'ctl_api_req_log@index')->name('admin.api_req_log.index');
        Route::match(['GET', 'POST'], 'api_req_log/delete', 'ctl_api_req_log@delete')->name('admin.api_req_log.delete');
        Route::match(['GET', 'POST'], 'api_req_log/export_list', 'ctl_api_req_log@export_list')->name('admin.api_req_log.export_list');
        Route::match(['GET', 'POST'], 'redis_info', 'ctl_cache@redis_info')->name('admin.cache.redis_info');
        Route::match(['GET', 'POST'], 'redis_keys', 'ctl_cache@redis_keys')->name('admin.cache.redis_keys');
        Route::match(['GET', 'POST'], 'cache/delete', 'ctl_cache@delete')->name('admin.cache.delete');
        Route::match(['GET', 'POST'], 'cache/detail', 'ctl_cache@detail')->name('admin.cache.detail');
        Route::match(['GET', 'POST'], 'config', 'ctl_config@index')->name('admin.config.index');
        Route::match(['GET', 'POST'], 'config/add', 'ctl_config@add')->name('admin.config.add');
        Route::match(['GET', 'POST'], 'config/edit', 'ctl_config@edit')->name('admin.config.edit');
        Route::match(['GET', 'POST'], 'config/delete', 'ctl_config@delete')->name('admin.config.delete');
        Route::match(['GET', 'POST'], 'config/export_list', 'ctl_config@export_list')->name('admin.config.export_list');
        Route::match(['GET', 'POST'], 'member', 'ctl_member@index')->name('admin.member.index');
        Route::match(['GET', 'POST'], 'member/edit', 'ctl_member@edit')->name('admin.member.edit');
        Route::match(['GET', 'POST'], 'member/enable', 'ctl_member@enable')->name('admin.member.enable');
        Route::match(['GET', 'POST'], 'member/disable', 'ctl_member@disable')->name('admin.member.disable');
        Route::match(['GET', 'POST'], 'member/export_list', 'ctl_member@export_list')->name('admin.member.export_list');
        Route::match(['GET', 'POST'], 'member/import', 'ctl_member@import')->name('admin.member.import');
        Route::match(['GET', 'POST'], 'member_level', 'ctl_member_level@index')->name('admin.member_level.index');
        Route::match(['GET', 'POST'], 'member_level/add', 'ctl_member_level@add')->name('admin.member_level.add');
        Route::match(['GET', 'POST'], 'member_level/edit', 'ctl_member_level@edit')->name('admin.member_level.edit');
        Route::match(['GET', 'POST'], 'member_level/delete', 'ctl_member_level@delete')->name('admin.member_level.delete');
        Route::match(['GET', 'POST'], 'member_level/export_list', 'ctl_member_level@export_list')->name('admin.member_level.export_list');
        Route::match(['GET', 'POST'], 'h5', 'ctl_h5@index')->name('admin.h5.index');
        Route::match(['GET', 'POST'], 'h5/add', 'ctl_h5@add')->name('admin.h5.add');
        Route::match(['GET', 'POST'], 'h5/edit', 'ctl_h5@edit')->name('admin.h5.edit');
        Route::match(['GET', 'POST'], 'h5/delete', 'ctl_h5@delete')->name('admin.h5.delete');
        Route::match(['GET', 'POST'], 'h5/enable', 'ctl_h5@enable')->name('admin.h5.enable');
        Route::match(['GET', 'POST'], 'h5/disable', 'ctl_h5@disable')->name('admin.h5.disable');
        Route::match(['GET', 'POST'], 'h5/export_list', 'ctl_h5@export_list')->name('admin.h5.export_list');
        Route::match(['GET', 'POST'], 'h5/detail', 'ctl_h5@detail')->name('admin.h5.detail');
        Route::match(['GET', 'POST'], 'report/member_increase_data', 'ctl_report@member_increase_data')->name('admin.report.member_increase_data');
        Route::match(['GET', 'POST'], 'report/member_increase_data_export', 'ctl_report@export_list')->name('admin.report.export_list');
        Route::match(['GET', 'POST'], 'news', 'ctl_news@index')->name('admin.news.index');
        Route::match(['GET', 'POST'], 'news/add', 'ctl_news@add')->name('admin.news.add');
        Route::match(['GET', 'POST'], 'news/edit', 'ctl_news@edit')->name('admin.news.edit');
        Route::match(['GET', 'POST'], 'news/delete', 'ctl_news@delete')->name('admin.news.delete');
        Route::match(['GET', 'POST'], 'news/enable', 'ctl_news@enable')->name('admin.news.enable');
        Route::match(['GET', 'POST'], 'news/disable', 'ctl_news@disable')->name('admin.news.disable');
        Route::match(['GET', 'POST'], 'news/export_list', 'ctl_news@export_list')->name('admin.news.export_list');
        Route::match(['GET', 'POST'], 'news_cat', 'ctl_news_cat@index')->name('admin.news_cat.index');
        Route::match(['GET', 'POST'], 'news_cat/add', 'ctl_news_cat@add')->name('admin.news_cat.add');
        Route::match(['GET', 'POST'], 'news_cat/edit', 'ctl_news_cat@edit')->name('admin.news_cat.edit');
        Route::match(['GET', 'POST'], 'news_cat/delete', 'ctl_news_cat@delete')->name('admin.news_cat.delete');
        Route::match(['GET', 'POST'], 'news_cat/enable', 'ctl_news_cat@enable')->name('admin.news_cat.enable');
        Route::match(['GET', 'POST'], 'news_cat/disable', 'ctl_news_cat@disable')->name('admin.news_cat.disable');
        Route::match(['GET', 'POST'], 'news_cat/export_list', 'ctl_news_cat@export_list')->name('admin.news_cat.export_list');
        Route::match(['GET', 'POST'], 'example', 'ctl_example@index')->name('admin.example.index');
        Route::match(['GET', 'POST'], 'example/detail', 'ctl_example@detail')->name('admin.example.detail');
        Route::match(['GET', 'POST'], 'example/add', 'ctl_example@add')->name('admin.example.add');
        Route::match(['GET', 'POST'], 'example/edit', 'ctl_example@edit')->name('admin.example.edit');
        Route::match(['GET', 'POST'], 'example/delete', 'ctl_example@delete')->name('admin.example.delete');
        Route::match(['GET', 'POST'], 'example/enable', 'ctl_example@enable')->name('admin.example.enable');
        Route::match(['GET', 'POST'], 'example/disable', 'ctl_example@disable')->name('admin.example.disable');
        Route::match(['GET', 'POST'], 'example/export_list', 'ctl_example@export_list')->name('admin.example.export_list');
        Route::match(['GET', 'POST'], 'collect_news', 'ctl_collect_news@index')->name('admin.collect_news.index');
        Route::match(['GET', 'POST'], 'collect_news/edit', 'ctl_collect_news@edit')->name('admin.collect_news.edit');
        Route::match(['GET', 'POST'], 'collect_news/delete', 'ctl_collect_news@delete')->name('admin.collect_news.delete');
        Route::match(['GET', 'POST'], 'collect_news/move', 'ctl_collect_news@move')->name('admin.collect_news.move');
        Route::match(['GET', 'POST'], 'collect_news/export_list', 'ctl_collect_news@export_list')->name('admin.collect_news.export_list');
        Route::match(['GET', 'POST'], 'collect_news/detail', 'ctl_collect_news@detail')->name('admin.collect_news.detail');
        Route::match(['GET', 'POST'], 'collect_web', 'ctl_collect_web@index')->name('admin.collect_web.index');
        Route::match(['GET', 'POST'], 'collect_web/add', 'ctl_collect_web@add')->name('admin.collect_web.add');
        Route::match(['GET', 'POST'], 'collect_web/edit', 'ctl_collect_web@edit')->name('admin.collect_web.edit');
        Route::match(['GET', 'POST'], 'collect_web/delete', 'ctl_collect_web@delete')->name('admin.collect_web.delete');
        Route::match(['GET', 'POST'], 'collect_web/enable', 'ctl_collect_web@enable')->name('admin.collect_web.enable');
        Route::match(['GET', 'POST'], 'collect_web/disable', 'ctl_collect_web@disable')->name('admin.collect_web.disable');
        Route::match(['GET', 'POST'], 'collect_web/export_list', 'ctl_collect_web@export_list')->name('admin.collect_web.export_list');
        Route::match(['GET', 'POST'], 'channel', 'ctl_channel@index')->name('admin.channel.index');
        Route::match(['GET', 'POST'], 'channel/add', 'ctl_channel@add')->name('admin.channel.add');
        Route::match(['GET', 'POST'], 'channel/edit', 'ctl_channel@edit')->name('admin.channel.edit');
        Route::match(['GET', 'POST'], 'channel/delete', 'ctl_channel@delete')->name('admin.channel.delete');
        Route::match(['GET', 'POST'], 'channel/enable', 'ctl_channel@enable')->name('admin.channel.enable');
        Route::match(['GET', 'POST'], 'channel/disable', 'ctl_channel@disable')->name('admin.channel.disable');
        Route::match(['GET', 'POST'], 'channel/export_list', 'ctl_channel@export_list')->name('admin.channel.export_list');
        Route::match(['GET', 'POST'], 'country', 'ctl_country@index')->name('admin.country.index');
        Route::match(['GET', 'POST'], 'country/add', 'ctl_country@add')->name('admin.country.add');
        Route::match(['GET', 'POST'], 'country/edit', 'ctl_country@edit')->name('admin.country.edit');
        Route::match(['GET', 'POST'], 'country/delete', 'ctl_country@delete')->name('admin.country.delete');
        Route::match(['GET', 'POST'], 'country/enable', 'ctl_country@enable')->name('admin.country.enable');
        Route::match(['GET', 'POST'], 'country/disable', 'ctl_country@disable')->name('admin.country.disable');
        Route::match(['GET', 'POST'], 'country/export_list', 'ctl_country@export_list')->name('admin.country.export_list');
        Route::match(['GET', 'POST'], 'shop', 'ctl_shop@index')->name('admin.shop.index');
        Route::match(['GET', 'POST'], 'shop/add', 'ctl_shop@add')->name('admin.shop.add');
        Route::match(['GET', 'POST'], 'shop/edit', 'ctl_shop@edit')->name('admin.shop.edit');
        Route::match(['GET', 'POST'], 'shop/delete', 'ctl_shop@delete')->name('admin.shop.delete');
        Route::match(['GET', 'POST'], 'shop/enable', 'ctl_shop@enable')->name('admin.shop.enable');
        Route::match(['GET', 'POST'], 'shop/disable', 'ctl_shop@disable')->name('admin.shop.disable');
        Route::match(['GET', 'POST'], 'shop/export_list', 'ctl_shop@export_list')->name('admin.shop.export_list');
        Route::match(['GET', 'POST'], 'store', 'ctl_store@index')->name('admin.store.index');
        Route::match(['GET', 'POST'], 'store/add', 'ctl_store@add')->name('admin.store.add');
        Route::match(['GET', 'POST'], 'store/edit', 'ctl_store@edit')->name('admin.store.edit');
        Route::match(['GET', 'POST'], 'store/delete', 'ctl_store@delete')->name('admin.store.delete');
        Route::match(['GET', 'POST'], 'store/enable', 'ctl_store@enable')->name('admin.store.enable');
        Route::match(['GET', 'POST'], 'store/disable', 'ctl_store@disable')->name('admin.store.disable');
        Route::match(['GET', 'POST'], 'store/export_list', 'ctl_store@export_list')->name('admin.store.export_list');
        Route::match(['GET', 'POST'], 'goods', 'ctl_goods@index')->name('admin.goods.index');
        Route::match(['GET', 'POST'], 'goods/add', 'ctl_goods@add')->name('admin.goods.add');
        Route::match(['GET', 'POST'], 'goods/edit', 'ctl_goods@edit')->name('admin.goods.edit');
        Route::match(['GET', 'POST'], 'goods/delete', 'ctl_goods@delete')->name('admin.goods.delete');
        Route::match(['GET', 'POST'], 'goods/enable', 'ctl_goods@enable')->name('admin.goods.enable');
        Route::match(['GET', 'POST'], 'goods/disable', 'ctl_goods@disable')->name('admin.goods.disable');
        Route::match(['GET', 'POST'], 'goods/export_list', 'ctl_goods@export_list')->name('admin.goods.export_list');
        Route::match(['GET', 'POST'], 'goods_cat', 'ctl_goods_cat@index')->name('admin.goods_cat.index');
        Route::match(['GET', 'POST'], 'goods_cat/add', 'ctl_goods_cat@add')->name('admin.goods_cat.add');
        Route::match(['GET', 'POST'], 'goods_cat/edit', 'ctl_goods_cat@edit')->name('admin.goods_cat.edit');
        Route::match(['GET', 'POST'], 'goods_cat/delete', 'ctl_goods_cat@delete')->name('admin.goods_cat.delete');
        Route::match(['GET', 'POST'], 'goods_cat/enable', 'ctl_goods_cat@enable')->name('admin.goods_cat.enable');
        Route::match(['GET', 'POST'], 'goods_cat/disable', 'ctl_goods_cat@disable')->name('admin.goods_cat.disable');
        Route::match(['GET', 'POST'], 'goods_cat/export_list', 'ctl_goods_cat@export_list')->name('admin.goods_cat.export_list');
        Route::match(['GET', 'POST'], 'goods_color', 'ctl_goods_color@index')->name('admin.goods_color.index');
        Route::match(['GET', 'POST'], 'goods_color/add', 'ctl_goods_color@add')->name('admin.goods_color.add');
        Route::match(['GET', 'POST'], 'goods_color/edit', 'ctl_goods_color@edit')->name('admin.goods_color.edit');
        Route::match(['GET', 'POST'], 'goods_color/delete', 'ctl_goods_color@delete')->name('admin.goods_color.delete');
        Route::match(['GET', 'POST'], 'goods_color/enable', 'ctl_goods_color@enable')->name('admin.goods_color.enable');
        Route::match(['GET', 'POST'], 'goods_color/disable', 'ctl_goods_color@disable')->name('admin.goods_color.disable');
        Route::match(['GET', 'POST'], 'goods_color/export_list', 'ctl_goods_color@export_list')->name('admin.goods_color.export_list');
        Route::match(['GET', 'POST'], 'sys_sms', 'ctl_sys_sms@index')->name('admin.sys_sms.index');
        Route::match(['GET', 'POST'], 'sys_sms/add', 'ctl_sys_sms@add')->name('admin.sys_sms.add');
        Route::match(['GET', 'POST'], 'sys_sms/delete', 'ctl_sys_sms@delete')->name('admin.sys_sms.delete');
        Route::match(['GET', 'POST'], 'sys_sms/export_list', 'ctl_sys_sms@export_list')->name('admin.sys_sms.export_list');
        Route::match(['GET', 'POST'], 'sys_sms_log', 'ctl_sys_sms_log@index')->name('admin.sys_sms_log.index');
        Route::match(['GET', 'POST'], 'sys_sms_log/delete', 'ctl_sys_sms_log@delete')->name('admin.sys_sms_log.delete');
        Route::match(['GET', 'POST'], 'sys_sms_log/export_list', 'ctl_sys_sms_log@export_list')->name('admin.sys_sms_log.export_list');
        Route::match(['GET', 'POST'], 'order', 'ctl_order@index')->name('admin.order.index');
        Route::get('500', function () {
            abort(500, '抱歉，服务器出了小差，请稍候再试！');
        });
    });
});


