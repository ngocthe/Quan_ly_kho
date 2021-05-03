<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', 'Api\System\AuthController@login');
    Route::post('token', 'Api\System\AuthController@createToken');
    Route::get('logout', 'Api\System\AuthController@logout');
    Route::post('refresh', 'Api\System\AuthController@refresh');
    Route::get('me', 'Api\System\AuthController@me');
    Route::post('password/change', 'Api\System\AuthController@changePassword');
});

Route::group(
    [
        'middleware' =>
        [
            'auth',
            'permission',
        ],
    ],
    function () {
        Route::post('images/upload', 'Api\System\UserController@uploadImage');
        Route::post('users/avatar', 'Api\System\UserController@changeAvatar');
        Route::apiResource('users', 'Api\System\UserController');
        Route::put('roles/{role}/actions', 'Api\System\RoleController@updateActions');
        Route::apiResource('roles', 'Api\System\RoleController');
        Route::apiResource('actions', 'Api\System\ActionController');
        Route::get('action-groups/detail', 'Api\System\ActionGroupController@detail');
        Route::apiResource('action-groups', 'Api\System\ActionGroupController');
        Route::get('menus/list', 'Api\System\MenuController@getMenuList');
        Route::apiResource('menus', 'Api\System\MenuController');
    }
);

Route::group(
    [
        'middleware' =>
        [
        ],
    ],
    function () {
        Route::apiResource('xes', 'Api\Business\XeController');
        Route::apiResource('phelieus', 'Api\Business\PheLieuController');
        Route::apiResource('khachhangs', 'Api\Business\KhachHangController');
        Route::apiResource('doitacs', 'Api\Business\DoiTacController');
        Route::apiResource('khos', 'Api\Business\KhoController');
        Route::apiResource('taikhoans', 'Api\Business\TaiKhoanController');
        Route::apiResource('baoves', 'Api\Business\BaoVeController');
        Route::apiResource('thukhos', 'Api\Business\ThuKhoController');
        Route::apiResource('nvbhs', 'Api\Business\NhanVienBanHangController');
        Route::apiResource('nhapkhos', 'Api\Business\NhapKhoController');
        Route::apiResource('phanloais', 'Api\Business\PhanLoaiController');
        Route::apiResource('xuatkhos', 'Api\Business\XuatKhoController');
        Route::get('phanloai/export', 'Api\Business\PhanLoaiController@export');

       
    }

);

