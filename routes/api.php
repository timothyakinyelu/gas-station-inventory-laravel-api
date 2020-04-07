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

        /*
        |-------------------------------------------------------------------------------
        | Display all products types
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/product-types
        | Controller:     Api\ProductsController@productType
        | Method:         GET
        | Description:    Fetch all products from db
        */
        Route::get('product-types', 'Api\ProductsController@productType');

        /*
        |-------------------------------------------------------------------------------
        | Display all products codes
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/product-codes
        | Controller:     Api\ProductsController@productCode
        | Method:         GET
        | Description:    Fetch all products from db
        */
        Route::get('product-codes', 'Api\ProductsController@productCode');

        Route::group(['middleware' => ['admin']], function() {
            /*
            |-------------------------------------------------------------------------------
            | Display all products
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/products
            | Controller:     Api\ProductsController@index
            | Method:         GET
            | Description:    Fetch all products from db
            */
            Route::get('products', 'Api\ProductsController@index');

            /*
            |-------------------------------------------------------------------------------
            | Add a new product
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/products
            | Controller:     Api\ProductsController@store
            | Method:         POST
            | Description:    Store a new product in the db
            */
            Route::post('/products', 'Api\ProductsController@store');

            /*
            |-------------------------------------------------------------------------------
            | Get a product to edit
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/products/{id}/edit
            | Controller:     Api\ProductsController@getProductToEdit
            | Method:         GET
            | Description:    Fetch a product to edit
            */
            Route::get('products/{id}/edit', 'Api\ProductsController@getProductToEdit');

            /*
            |-------------------------------------------------------------------------------
            | Update a product
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/products/{id}/update
            | Controller:     Api\ProductsController@update
            | Method:         PUT
            | Description:    Update a product in the db
            */
            Route::put('products/{id}/update', 'Api\ProductsController@update');

            /*
            |-------------------------------------------------------------------------------
            | Delete a product
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/products/{id}
            | Controller:     Api\ProductsController@delete
            | Method:         DELETE
            | Description:    Remove a product from the db
            */
            Route::delete('products/{id}', 'Api\ProductsController@delete');

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
            | URL:            /api/v2/salesbyStation/{id}
            | Controller:     Api\SalesController@stationSales
            | Method:         GET
            | Description:    Fetch all sales by outlet from  db
            */
            Route::get('salesbystation/{id}', 'Api\SalesController@getSalesByStation');

            /*
            |-------------------------------------------------------------------------------
            | Display details of day sales sum
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/salesbydate/{id}/{product_code_id}/{date}
            | Controller:     Api\SalesController@getSalesByDate
            | Method:         GET
            | Description:    Fetch details of a cumulative sale from db
            */
            Route::get('salesbydate/{id}/{product_code_id}/{date}', 'Api\SalesController@getSalesByDate');

            /*
            |-------------------------------------------------------------------------------
            | Get sale to edit
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/sales/{id}/edit
            | Controller:     Api\SalesController@getSaleToEdit
            | Method:         GET
            | Description:    Fetch a sale to edit
            */
            Route::get('sales/{id}/edit', 'Api\SalesController@getSaleToEdit');

            /*
            |-------------------------------------------------------------------------------
            | Update a sale
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/sales/{id}/update
            | Controller:     Api\SalesController@update
            | Method:         PUT
            | Description:    Update a sale
            */
            Route::put('sales/{id}/update', 'Api\SalesController@update');

            /*
            |-------------------------------------------------------------------------------
            | Delete an individual or multiple sales from db
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/sales/{id}
            | Controller:     Api\SalesController@delete
            | Method:         DELETE
            | Description:    Delete sale item from table
            */
            Route::delete('sales/{id}', 'Api\SalesController@delete');
        });
    });
});