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

Route::group(['prefix' => 'v2'], function(){
    Route::post('login', 'Api\AuthController@login');

    Route::group(['middleware' => ['auth:api']], function() {
        /*
        |-------------------------------------------------------------------------------
        | Gets authenticated user
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/me
        | Controller:     Api\AuthController@me
        | Method:         GET
        | Description:    Fetch authenticated user details
        */
        Route::get('me', 'Api\AuthController@me');

        Route::group(['middleware' => ['admin']], function() {
            /*
            |-------------------------------------------------------------------------------
            | Display all service outlets of a company
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/stations/{id}
            | Controller:     Api\StationsController@index
            | Method:         GET
            | Description:    Fetch all stations of a company from db
            */
            Route::get('stations/{id}', 'Api\StationsController@index');

            /*
            |-------------------------------------------------------------------------------
            | Display sales by month
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/chartDataBymonth
            | Controller:     Api\StationsController@getChartDataByMonth
            | Method:         GET
            | Description:    Fetch data by month
            */
            Route::get('chartDataBymonth', 'Api\StationsController@getChartDataByMonth');

            /*
            |-------------------------------------------------------------------------------
            | Display sum of current month sales
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/currentMonthSalesSum
            | Controller:     Api\StationsController@getAllSalesByMonth
            | Method:         GET
            | Description:    Fetch sum of current month sales
            */
            Route::get('currentMonthSalesSum', 'Api\StationsController@getSalesByCurrentMonth');

            /*
            |-------------------------------------------------------------------------------
            | Display sum of current month expenses
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/currentMonthExpensesSum
            | Controller:     Api\StationsController@getExpensesByCurrentMonth
            | Method:         GET
            | Description:    Fetch sum of current month expenses
            */
            Route::get('currentMonthExpensesSum', 'Api\StationsController@getExpensesByCurrentMonth');
        });
    });
});