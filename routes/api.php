<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\PassportAuthController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['guest'])->group(function () {
    Route::post('register', [PassportAuthController::class, 'register']);
    Route::post('login', [PassportAuthController::class, 'login']);
});
Route::middleware('auth:api')->group(function () {
    Route::get('get-user', [PassportAuthController::class, 'userInfo']);
    Route::post('logout', [PassportAuthController::class, 'logout'])->name('logout');

});


Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('items', 'App\Http\Controllers\ItemController');
    Route::post('item/approved/{id}', [ItemController::class, 'approved_item']);
    Route::get('item/filter', [ItemController::class, 'filter_items']);
    Route::get('search_items', [ItemController::class, 'search_items']);
    Route::get('statistics', [ItemController::class, 'statistics']);
    Route::get('today_tasks', [ItemController::class, 'today_tasks']);
    Route::get('send_mail', [ItemController::class, 'sendMail']);
    Route::get('data', [ItemController::class, 'data']);
    Route::get('get_logs', [ItemController::class, 'get_logs']);
    Route::get('logs_filter', [ItemController::class, 'logs_filter']);
    Route::get('get_log_by_id/{id}', [ItemController::class, 'get_log_by_id']);

});

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('banks', 'App\Http\Controllers\BankController');
    Route::apiResource('cashes', '\App\Http\Controllers\CashController');

    Route::get('check_balance/{id}', [\App\Http\Controllers\BankController::class, 'check_balance']);
    Route::patch('update_check_balance/{id}', [\App\Http\Controllers\BankController::class, 'update_check_balance']);

    Route::get('cash_check_balance/{id}', [\App\Http\Controllers\CashController::class, 'check_balance']);
    Route::patch('cash_update_check_balance/{id}', [\App\Http\Controllers\CashController::class, 'update_check_balance']);

});

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('companies', 'App\Http\Controllers\CompanyController');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('projects', 'App\Http\Controllers\ProjectController');
    Route::get('filter_project/{id}', [\App\Http\Controllers\ProjectController::class, 'filter_project_details']);


    Route::apiResource('users', 'App\Http\Controllers\UserController');
});
