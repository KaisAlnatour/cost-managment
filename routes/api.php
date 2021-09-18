<?php

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

Route::post('register', 'API\UserController@register');
Route::post('login', 'API\UserController@login');

Route::get('all-user', 'API\UserController@getAllUser');                        // All Users

Route::middleware('auth:api')->group(function () {
// ItemCost
    Route::group(['prefix'=>'ItemCost'],function () {
        Route::post('addItemCost', 'API\ItemCostController@addItemCost');
        Route::put('editItemCost', 'API\ItemCostController@editItemCost');
        Route::delete('deleteItemCost/{code}', 'API\ItemCostController@deleteItemCost');
        Route::get('showItemCostByCode/{code}', 'API\ItemCostController@showItemCostByCode');
        Route::get('showAllItemCost', 'API\ItemCostController@showAllItemCost');
    });

    // ExpansiveCost
    Route::group(['prefix'=>'ExpansiveCost'],function () {
        Route::post('addExpansiveCost', 'API\ExpansiveCostController@addExpansiveCost');
        Route::put('editExpansiveCost', 'API\ExpansiveCostController@editExpansiveCost');
        Route::delete('deleteExpansiveCost/{id}', 'API\ExpansiveCostController@deleteExpansiveCost');
        Route::get('showExpansiveCostById/{id}', 'API\ExpansiveCostController@showExpansiveCostById');
        Route::get('showAllExpansiveCost', 'API\ExpansiveCostController@showAllExpansiveCost');
        Route::get('showItemsByExpansiveCode/{code}', 'API\ExpansiveCostController@showExpansiveCostByItemCostCode');
        Route::put('changeState/{id}/{state}', 'API\ExpansiveCostController@changeState');
        Route::post('endPointMonth', 'API\ExpansiveCostController@endPointMonth');
        Route::post('endPointYear', 'API\ExpansiveCostController@endPointYear');
        Route::post('endPointAllMonthInYear', 'API\ExpansiveCostController@endPointAllMonthInYear');
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
