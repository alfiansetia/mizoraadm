<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\RadeemController;
use App\Http\Controllers\Api\RewardController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SliderController;

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


Route::post('login', [AuthController::class, 'login']);
Route::post('verify_otp', [AuthController::class, 'verify_otp']);
Route::get('get_profile', [AuthController::class, 'get_profile']);
Route::put('update_profile', [AuthController::class, 'update_profile']);
Route::post('logout', [AuthController::class, 'logout']);

Route::get('provinces', [ProvinceController::class, 'index']);
Route::get('cities', [CityController::class, 'index']);

Route::get('location', [LocationController::class, 'index']);
Route::get('reward', [RewardController::class, 'index']);
Route::get('membership', [MembershipController::class, 'index']);

Route::get('setting', [SettingController::class, 'index']);
Route::get('slider', [SliderController::class, 'index']);
Route::get('message', [MessageController::class, 'index']);

Route::get('radeems', [RadeemController::class, 'index']);

Route::group(['middleware' => 'auth:api'], function () {


	Route::get('profile', [AuthController::class, 'profile']);
	Route::post('change-password', [AuthController::class, 'changePassword']);
	Route::post('update-profile', [AuthController::class, 'updateProfile']);

	//only those have manage_user permission will get access
	Route::group(['middleware' => 'can:manage_user'], function () {
		Route::get('/users', [UserController::class, 'list']);
		Route::post('/user/create', [UserController::class, 'store']);
		Route::get('/user/{id}', [UserController::class, 'profile']);
		Route::get('/user/delete/{id}', [UserController::class, 'delete']);
		Route::post('/user/change-role/{id}', [UserController::class, 'changeRole']);
	});

	//only those have manage_role permission will get access
	Route::group(['middleware' => 'can:manage_role|manage_user'], function () {
		Route::get('/roles', [RolesController::class, 'list']);
		Route::post('/role/create', [RolesController::class, 'store']);
		Route::get('/role/{id}', [RolesController::class, 'show']);
		Route::get('/role/delete/{id}', [RolesController::class, 'delete']);
		Route::post('/role/change-permission/{id}', [RolesController::class, 'changePermissions']);
	});


	//only those have manage_permission permission will get access
	Route::group(['middleware' => 'can:manage_permission|manage_user'], function () {
		Route::get('/permissions', [PermissionController::class, 'list']);
		Route::post('/permission/create', [PermissionController::class, 'store']);
		Route::get('/permission/{id}', [PermissionController::class, 'show']);
		Route::get('/permission/delete/{id}', [PermissionController::class, 'delete']);
	});
});
