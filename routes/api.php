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
            | URL:            /api/v2/chartDataBymonth/{id}
            | Controller:     Api\StationsController@getChartDataByMonth
            | Method:         GET
            | Description:    Fetch data by month
            */
            Route::get('chartDataBymonth/{id}', 'Api\StationsController@getChartDataByMonth');

            /*
            |-------------------------------------------------------------------------------
            | Display sum of current month sales
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/currentMonthSalesSum/{id}
            | Controller:     Api\StationsController@getAllSalesByMonth
            | Method:         GET
            | Description:    Fetch sum of current month sales
            */
            Route::get('currentMonthSalesSum/{id}', 'Api\StationsController@getSalesByCurrentMonth');

            /*
            |-------------------------------------------------------------------------------
            | Display sum of current month expenses
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/currentMonthExpensesSum/{id}
            | Controller:     Api\StationsController@getExpensesByCurrentMonth
            | Method:         GET
            | Description:    Fetch sum of current month expenses
            */
            Route::get('currentMonthExpensesSum/{id}', 'Api\StationsController@getExpensesByCurrentMonth');

            /*
            |-------------------------------------------------------------------------------
            | Display all sales of a service outlet
            |-------------------------------------------------------------------------------
            | URL:            /api/v1/salesbyStation/{id}
            | Controller:     Api\SalesController@stationSales
            | Method:         GET
            | Description:    Fetch all sales by outlet from  db
            */
            Route::get('salesbystation/{id}', 'Api\SalesController@getSalesByStation');
        });
    });
});