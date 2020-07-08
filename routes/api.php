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
    /*
    |-------------------------------------------------------------------------------
    | Request for a password reset link
    |-------------------------------------------------------------------------------
    | URL:            /api/v2/settings/sendPasswordResetLink
    | Controller:     Api\ResetPasswordController@sendEmail
    | Method:         POST
    | Description:    Reset password link
    */
    Route::post('/settings/sendPasswordResetLink', 'Api\ResetPasswordController@sendEmail');

    /*
    |-------------------------------------------------------------------------------
    | Change password
    |-------------------------------------------------------------------------------
    | URL:            /api/v2/settings/settings/resetPassword
    | Controller:     Api\ChangePasswordController@sendEmail
    | Method:         POST
    | Description:    Change password
    */
    Route::post('/settings/resetPassword', 'Api\ChangePasswordController@process');

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
        | Store wet sales
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/sales/sale/wet
        | Controller:     Api\SalesController@storeWet
        | Method:         POST
        | Description:    Store a sale in the db
        */
        Route::post('sales/sale/wet', 'Api\SalesController@storeWet');

        /*
        |-------------------------------------------------------------------------------
        | Store dry sales
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/sales/sale/dry
        | Controller:     Api\SalesController@storeDry
        | Method:         POST
        | Description:    Store a sale in the db
        */
        Route::post('sales/sale/dry', 'Api\SalesController@storeDry');

        /*
        |-------------------------------------------------------------------------------
        | Display wet sales by product id
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/sales/sale/wet/{stationId}/{productId}/{date}
        | Controller:     Api\SalesController@getDaySalesByProductId
        | Method:         GET
        | Description:    Fetch sales by product id from db
        */
        Route::get('sales/sale/wet/{stationId}/{productId}/{date}', 'Api\SalesController@getDaySalesByProductId');

        /*
        |-------------------------------------------------------------------------------
        | Display dry sales by product id
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/sales/sale/dry/{stationId}/{productCodeId}/{date}
        | Controller:     Api\SalesController@getDaySalesByProductId
        | Method:         GET
        | Description:    Fetch sales by product id from db
        */
        Route::get('sales/sale/dry/{stationId}/{productCodeId}/{date}', 'Api\SalesController@getDaySalesByProductCodeId');

        /*
        |-------------------------------------------------------------------------------
        | Store wet stocks
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/stocks/stock/wet
        | Controller:     Api\StocksController@storeWetStock
        | Method:         POST
        | Description:    Store a stock in the db
        */
        Route::post('stocks/stock/wet', 'Api\StocksController@storeWetStock');

        /*
        |-------------------------------------------------------------------------------
        | Store dry sales
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/stocks/stock/dry
        | Controller:     Api\StocksController@storeDryStock
        | Method:         POST
        | Description:    Store a stock in the db
        */
        Route::post('stocks/stock/dry', 'Api\StocksController@storeDryStock');

        /*
        |-------------------------------------------------------------------------------
        | Display wet stocks by product id
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/stocks/stock/wet/{stationId}/{productId}/{date}
        | Controller:     Api\StocksController@getDayStocksByProductId
        | Method:         GET
        | Description:    Fetch stocks by product id from db
        */
        Route::get('stocks/stock/wet/{stationId}/{productId}/{date}', 'Api\StocksController@getDayStocksByProductId');

        /*
        |-------------------------------------------------------------------------------
        | Display dry stocks by product id
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/stocks/stock/dry/{stationId}/{productCodeId}/{date}
        | Controller:     Api\StocksController@getDayStocksByProductId
        | Method:         GET
        | Description:    Fetch stocks by product id from db
        */
        Route::get('stocks/stock/dry/{stationId}/{productCodeId}/{date}', 'Api\StocksController@getDayStocksByProductCodeId');

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

        /*
        |-------------------------------------------------------------------------------
        | Display product by product code id
        |-------------------------------------------------------------------------------
        | URL:            /api/v1/products/{id}
        | Controller:     Api\ProductsController@getProductByCodeId
        | Method:         GET
        | Description:    Fetch product from db
        */
        Route::get('productCode/products/{companyID}/{id}', 'Api\ProductsController@getProductByCodeId');

        /*
        |-------------------------------------------------------------------------------
        | Store wet supplies
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/supplies/supply/wet
        | Controller:     Api\SuppliesController@storeWetSupply
        | Method:         POST
        | Description:    Store a supply in the db
        */
        Route::post('supplies/supply/wet', 'Api\SuppliesController@storeWetSupply');

        /*
        |-------------------------------------------------------------------------------
        | Store dry supplies
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/supplies/supply/dry
        | Controller:     Api\SuppliesController@storeDrySupply
        | Method:         POST
        | Description:    Store a supply in the db
        */
        Route::post('supplies/supply/dry', 'Api\SuppliesController@storeDrySupply');

        /*
        |-------------------------------------------------------------------------------
        | Display wet supplies by product id
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/supplies/supply/wet/{stationId}/{productId}/{date}
        | Controller:     Api\SuppliesController@getDaySuppliesByProductId
        | Method:         GET
        | Description:    Fetch supplies by product id from db
        */
        Route::get('supplies/supply/wet/{stationId}/{productId}/{date}', 'Api\SuppliesController@getDaySuppliesByProductId');

        /*
        |-------------------------------------------------------------------------------
        | Display dry supplies by product id
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/supplies/supplies/dry/{stationId}/{productCodeId}/{date}
        | Controller:     Api\SuppliesController@getDaySuppliesByProductId
        | Method:         GET
        | Description:    Fetch supplies by product id from db
        */
        Route::get('supplies/supply/dry/{stationId}/{productCodeId}/{date}', 'Api\SuppliesController@getDaySuppliesByProductCodeId');

        /*
        |-------------------------------------------------------------------------------
        | Store expense
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/expenses/expense
        | Controller:     Api\ExpensesController@storeExpense
        | Method:         POST
        | Description:    Store an expense in the db
        */
        Route::post('expenses/expense', 'Api\ExpensesController@storeExpense');

        /*
        |-------------------------------------------------------------------------------
        | Display expenses by station id
        |-------------------------------------------------------------------------------
        | URL:            /api/v2/sexpenses/expense/{stationId}/{date}
        | Controller:     Api\ExpensesController@getDayExpenses
        | Method:         GET
        | Description:    Fetch expenses by station id from db
        */
        Route::get('expenses/expense/{stationId}/{date}', 'Api\ExpensesController@getDayExpenses');

        Route::group(['middleware' => ['admin']], function() {
            /*
            |-------------------------------------------------------------------------------
            | Display all users of a company
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/users/{id}
            | Controller:     Api\UsersController@index
            | Method:         GET
            | Description:    Fetch all users in a company
            */
            Route::get('users/{id}', 'Api\UsersController@index');

            /*
            |-------------------------------------------------------------------------------
            | Add a new user
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/users
            | Controller:     Api\UsersController@create
            | Method:         POST
            | Description:    Store a new user in the db
            */
            Route::post('users', 'Api\UsersController@create');

            /*
            |-------------------------------------------------------------------------------
            | Get User to edit
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/users/{id}/edit
            | Controller:     Api\UsersController@getUserToEdit
            | Method:         GET
            | Description:    Fetch a user to edit
            */
            Route::get('users/{id}/edit', 'Api\UsersController@getUserToEdit');

            /*
            |-------------------------------------------------------------------------------
            | Update a user
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/users/{id}/update
            | Controller:     Api\UsersController@update
            | Method:         PUT
            | Description:    Update a user
            */
            Route::put('users/{id}/update', 'Api\UsersController@update');

            /*
            |-------------------------------------------------------------------------------
            | Delete a user
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/users/{id}
            | Controller:     Api\UsersController@delete
            | Method:         DELETE
            | Description:    Remove a user from the db
            */
            Route::delete('users/{id}', 'Api\UsersController@delete');

            
            /*
            |-------------------------------------------------------------------------------
            | Display all products
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/products/{id}
            | Controller:     Api\ProductsController@index
            | Method:         GET
            | Description:    Fetch all products from db
            */
            Route::get('products/{id}', 'Api\ProductsController@index');

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
            | Display sum of month sales by period
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/selectedMonthSalesSum/{id}/{from}/{to}
            | Controller:     Api\StationsController@getStationSalesByDate
            | Method:         GET
            | Description:    Fetch sum of month sales by period
            */
            Route::get('selectedMonthSalesSum/{id}/{from}/{to}', 'Api\StationsController@getStationSalesByDate');

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
            | Display sum of month expenses by period
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/selectedMonthExpensesSum/{id}/{from}/{to}
            | Controller:     Api\StationsController@getStationExpensesByDate
            | Method:         GET
            | Description:    Fetch sum of month expenses by period
            */
            Route::get('selectedMonthExpensesSum/{id}/{from}/{to}', 'Api\StationsController@getStationExpensesByDate');

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

            /*
            |-------------------------------------------------------------------------------
            | Display all products
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/employees/{id}
            | Controller:     Api\EmployeesController@index
            | Method:         GET
            | Description:    Fetch all employees from db
            */
            Route::get('/employees/{id}', 'Api\EmployeesController@index');

            /*
            |-------------------------------------------------------------------------------
            | Add a new employee
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/employees
            | Controller:     Api\EmployeesController@store
            | Method:         POST
            | Description:    Store a new employee in the db
            */
            Route::post('/employees', 'Api\EmployeesController@store');

            /*
            |-------------------------------------------------------------------------------
            | Get an employee to edit
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/employees/{id}/edit
            | Controller:     Api\EmployeesController@getEmployeeToEdit
            | Method:         GET
            | Description:    Fetch an employee to edit
            */
            Route::get('employees/{id}/edit', 'Api\EmployeesController@getEmployeeToEdit');

            /*
            |-------------------------------------------------------------------------------
            | Update an employee
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/employees/{id}/update
            | Controller:     Api\EmployeesController@update
            | Method:         PUT
            | Description:    Update an employee in the db
            */
            Route::put('employees/{id}/update', 'Api\EmployeesController@update');

            /*
            |-------------------------------------------------------------------------------
            | Delete an employee
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/employees/{id}
            | Controller:     Api\EmployeesController@delete
            | Method:         DELETE
            | Description:    Remove an employee record from the db
            */
            Route::delete('employees/{id}/delete', 'Api\EmployeesController@delete');

            /*
            |-------------------------------------------------------------------------------
            | Search for employee
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/employees/search
            | Controller:     Api\EmployeesController@employeeSearch
            | Method:         GET
            | Description:    Search in db
            */
            Route::get('/employees/{id}/search', 'Api\EmployeesController@employeeSearch');

             /*
            |-------------------------------------------------------------------------------
            | Display all stocks
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/stocks/{id}
            | Controller:     Api\StocksController@stationStocks
            | Method:         GET
            | Description:    Fetch all stock from db
            */
            Route::get('stocksbystation/{id}', 'Api\StocksController@getStocksByStation');

            /*
            |-------------------------------------------------------------------------------
            | Get stock to edit
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/stocks/{id}/edit
            | Controller:     Api\SuppliesController@getSupplyToEdit
            | Method:         GET
            | Description:    Fetch a supply to edit
            */
            Route::get('stocks/{id}/edit', 'Api\StocksController@getStockToEdit');

            /*
            |-------------------------------------------------------------------------------
            | Update a stock
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/stocks/{id}/update
            | Controller:     Api\StocksController@update
            | Method:         PUT
            | Description:    Update a stock
            */
            Route::put('stocks/{id}/update', 'Api\StocksController@update');

            /*
            |-------------------------------------------------------------------------------
            | Delete a stock
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/stocks/{id}
            | Controller:     Api\StocksController@delete
            | Method:         DELETE
            | Description:    Remove a stock record from the db
            */
            Route::delete('stocks/{id}', 'Api\StocksController@delete');

            /*
            |-------------------------------------------------------------------------------
            | Display all supplies
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/supplies/{id}
            | Controller:     Api\SuppliesController@stationStocks
            | Method:         GET
            | Description:    Fetch all supply from db
            */
            Route::get('suppliesbystation/{id}', 'Api\SuppliesController@getSuppliesByStation');

            /*
            |-------------------------------------------------------------------------------
            | Get supply to edit
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/supplies/{id}/edit
            | Controller:     Api\SuppliesController@getSupplyToEdit
            | Method:         GET
            | Description:    Fetch a supply to edit
            */
            Route::get('supplies/{id}/edit', 'Api\SuppliesController@getSupplyToEdit');

            /*
            |-------------------------------------------------------------------------------
            | Update a supply
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/supplies/{id}/update
            | Controller:     Api\SuppliesController@update
            | Method:         PUT
            | Description:    Update a supply
            */
            Route::put('supplies/{id}/update', 'Api\SuppliesController@update');

            /*
            |-------------------------------------------------------------------------------
            | Delete a supply
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/supplies/{id}
            | Controller:     Api\SuppliesController@delete
            | Method:         DELETE
            | Description:    Remove a supply record from the db
            */
            Route::delete('supplies/{id}', 'Api\SuppliesController@delete');

            /*
            |-------------------------------------------------------------------------------
            | Display all expenses
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/expenses/{id}
            | Controller:     Api\ExpensesController@getExpensesByStation
            | Method:         GET
            | Description:    Fetch all expenses from db
            */
            Route::get('expensesbystation/{id}', 'Api\ExpensesController@getExpensesByStation');

            /*
            |-------------------------------------------------------------------------------
            | Display details day expense
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/station-day-expense/{id}/{date}
            | Controller:     Api\ExpensesController@getExpenseDetails
            | Method:         GET
            | Description:    Fetch details of a cumulative expense from db
            */
            Route::get('station-day-expense/{id}/{date}', 'Api\ExpensesController@getExpenseDetails');

            /*
            |-------------------------------------------------------------------------------
            | Get an expense to edit
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/expenses/{id}/edit
            | Controller:     Api\ExpensesController@getExpenseToEdit
            | Method:         GET
            | Description:    Fetch an expense to edit
            */
            Route::get('expenses/{id}/edit', 'Api\ExpensesController@getExpenseToEdit');

            /*
            |-------------------------------------------------------------------------------
            | Update an expense
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/expenses/{id}/update
            | Controller:     Api\ExpensesController@update
            | Method:         PUT
            | Description:    Update an expense in the db
            */
            Route::put('expenses/{id}/update', 'Api\ExpensesController@update');

            /*
            |-------------------------------------------------------------------------------
            | Delete an expense
            |-------------------------------------------------------------------------------
            | URL:            /api/v2/expenses/{id}
            | Controller:     Api\ExpensesController@delete
            | Method:         DELETE
            | Description:    Remove an expense record from the db
            */
            Route::delete('expenses/{id}', 'Api\ExpensesController@delete');
        });
    });
});