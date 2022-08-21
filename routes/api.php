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

Route::group(['prefix' => 'v1', 'middleware' => ['secretAPI']], function () {

    // start Dashboard auth
    Route::group(['prefix' => 'auth', 'namespace' => 'Dashboard'], function () {

        // start login

        Route::post('login', 'AuthDashboardController@login');

        // check token

        Route::get('checkToken',  'AuthDashboardController@authorizeUser');
    });

    // api token_access

    Route::middleware(['auth:api'])->group(function () {

        // start Dashboard
        Route::group(['prefix' => 'dashboard', 'namespace' => 'Dashboard'], function () {


            // start Notification
            //             Route::get('getAllNot','NotificationController@getAllNot');
            //             Route::get('getNotNotRead','NotificationController@getNotNotRead');
            //             Route::post('clearItem/{id}','NotificationController@clearItem');
            //             Route::post('getNotNotRead','NotificationController@clearAll');


            // start User
            Route::apiResource('user', 'UserController');

            // start role
            Route::resource('role', 'RoleController');


            // department
            Route::resource('department', 'DepartmentController');
            Route::get('activeDepartment', 'DepartmentController@activeDepartment');
            Route::get('activationDepartment/{id}', 'DepartmentController@activationDepartment');

            // job
            Route::resource('job', 'JobController');
            Route::get('activeJob', 'JobController@activeJob');
            Route::get('activationJob/{id}', 'JobController@activationJob');

            // employee
            Route::resource('employee', 'EmployeeController');
            Route::get('activationEmployee/{id}', 'EmployeeController@activationEmployee');
            Route::post('employee/changePassword/{id}', 'EmployeeController@changePassword');
            Route::get('role', 'EmployeeController@role');
            Route::get('salesEmployee', 'EmployeeController@salesEmployee');

            // category
            Route::resource('category', 'CategoryController')->except(['show']);
            Route::get('activationCategory/{id}', 'CategoryController@activationCategory');

            //Units
            Route::prefix("units")->group(function () {
                Route::get("", "UnitController@index");
                Route::post("", "UnitController@store");
                Route::put("", "UnitController@update");
                Route::delete("{id}", "UnitController@delete");
            });

            //Offers
            Route::prefix("offers")->group(function () {
                Route::get("", "OfferController@index");
                Route::post("", "OfferController@store");
                Route::put("", "OfferController@update");
                Route::delete("{id}", "OfferController@delete");
                Route::get("toggle-activation/{id}", "OfferController@toggleActivation");
            });

            //Offers
            Route::prefix("shippings")->group(function () {
                Route::get("", "ShippingController@index");
                Route::post("", "ShippingController@store");
                Route::put("", "ShippingController@update");
                Route::delete("{id}", "ShippingController@delete");
                Route::get("toggle-activation/{id}", "ShippingController@toggleActivation");
            });

            //Suppliers
            Route::prefix("suppliers")->group(function () {
                Route::get("", "SupplierController@index");
                Route::post("", "SupplierController@store");
                Route::put("", "SupplierController@update");
                Route::delete("{id}", "SupplierController@delete");
                Route::get("toggle-activation/{id}", "SupplierController@toggleActivation");
            });

            //start logout
            Route::post('logout', 'AuthDashboardController@logout');
        });
    });
});
