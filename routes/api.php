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

    // Start Dashboard Auth
    Route::group(['prefix' => 'auth', 'namespace' => 'Dashboard'], function () {

        // start login
        Route::post('login', 'AuthDashboardController@login');

        // check token
        Route::get('checkToken',  'AuthDashboardController@authorizeUser');
    });

    // api token_access

    Route::middleware(['auth:api'])->group(function () {

        // Start Dashboard
        Route::group(['prefix' => 'dashboard', 'namespace' => 'Dashboard'], function () {

            // start Notification
            //            Route::get('getAllNot','NotificationController@getAllNot');
            //            Route::get('getNotNotRead','NotificationController@getNotNotRead');
            //            Route::post('clearItem/{id}','NotificationController@clearItem');
            //            Route::post('getNotNotRead','NotificationController@clearAll');

            // examination record
            Route::resource('examinationRecord', 'ExaminationRecordController');

            // purchase return
            Route::resource('purchaseReturn', 'PurchaseReturnController');

            // purchase invoice
            Route::resource('purchaseInvoice', 'PurchaseController');

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

            // supplier
            Route::resource('supplier', 'SupplierController')->except(['show']);
            Route::get('activationSupplier/{id}', 'SupplierController@activationSupplier');

            // category
            Route::resource('category', 'CategoryController')->except(['show']);
            Route::get('activationCategory/{id}', 'CategoryController@activationCategory');

            // sub category
            Route::resource('subCategory', 'SubCategoryController')->except(['show']);
            Route::get('activationSubCategory/{id}', 'SubCategoryController@activationSubCategory');

            // users category
            Route::resource('usersCategory', 'UsersCategoryController')->except(['show']);
            Route::get('activationUsersCategory/{id}', 'UsersCategoryController@activationUsersCategory');

            // tax
            Route::resource('tax', 'TaxController')->except(['show']);
            Route::get('activationTax/{id}', 'TaxController@activationTax');

            // company
            Route::resource('company', 'CompanyController')->except(['show']);
            Route::get('activationCompany/{id}', 'CompanyController@activationCompany');

            // pharmacist form
            Route::resource('pharmacistForm', 'PharmacistFormController')->except(['show']);

            // product name
            Route::resource('productName', 'ProductNameController')->except(['show']);
            Route::get('activationProductName/{id}', 'ProductNameController@activationProductName');

            // alternative
            Route::resource('alternative', 'AlternativeController')->except(['show']);
            Route::get('activationAlternative/{id}', 'AlternativeController@activationAlternative');

            // product
            Route::resource('product', 'ProductController');
            Route::get('activationProduct/{id}', 'ProductController@activationProduct');

            // price
            Route::resource('price', 'PriceController')->except(['show']);

            // kayan price
            Route::resource('kayanPrice', 'KayanPriceController')->except(['show']);
            Route::get('kayanPrice/getProduct', 'KayanPriceController@getProduct');

            // selling method
            Route::resource('sellingMethod', 'SellingMethodController')->except(['show']);
            Route::get('activationSellingMethod/{id}', 'SellingMethodController@activationSaleMethod');

            // shift
            Route::resource('shift', 'ShiftController')->except(['show']);
            Route::get('activationShift/{id}', 'ShiftController@activationShift');

            // stock
            Route::resource('stock', 'StockController')->except(['show']);

            // virtual stock
            Route::resource('virtualStock', 'VirtualStockController')->except(['show']);

            // purchase
            Route::resource('purchase', 'PurchaseController')->except(['show']);

            // storage
            Route::resource('storage', 'StorageController')->except(['show']);

            // sale invoice
            Route::resource('saleInvoice', 'SaleController');

            // sale Record
            Route::resource('saleRecord', 'SaleRecordController');

            // sale return
            Route::resource('saleReturn', 'SaleReturnController');

            // complaint
            Route::resource('complaint', 'ComplaintController');
            Route::Post('replycomplaint/{id}', 'ComplaintController@reply');
            Route::get('showcomplaint/{id}', 'ComplaintController@show');


            // reports
            Route::resource('complaintReport', 'ComplaintReportController');
            Route::resource('saleReport', 'SaleReportController');
            Route::get('saleReportByProduct', 'SaleReportController@saleReportByProduct');
            Route::get('saleReportByCategory', 'SaleReportController@saleReportByCategory');
            Route::get('saleReportByReturn', 'SaleReportController@saleReportByReturn');


            //
            Route::get('purchaseInvoiceProduct', 'ProductController@purchaseInvoiceProduct');

            // refused
            Route::resource('refused', 'RefusedController')->except(['show']);

            // relations routes
            Route::get('getProductNames', 'ProductController@getProductNames');
            Route::get('getCategories', 'ProductController@getCategories');
            Route::get('getSubCategories', 'ProductController@getSubCategories');
            Route::get('getCompanies', 'ProductController@getCompanies');
            Route::get('getTaxes', 'ProductController@getTaxes');
            Route::get('getUnits', 'ProductController@getUnits');
            Route::get('getEmployees', 'StockController@getEmployees');
            Route::get('getShifts', 'StockController@getShifts');
            Route::get('getCities', 'StockController@getCities');
            Route::get('getAreas', 'StockController@getAreas');
            Route::get('getSuppliers', 'PurchaseController@getSuppliers');
            Route::get('getProducts', 'PurchaseController@getProducts');
            Route::get('productPrice/{id}','PurchaseController@productPrice');
            Route::get('getStocks', 'RefusedController@getStocks');
            Route::get('getClientBalance/{id}', 'SaleController@getClientBalance');


            //start logout
            Route::post('logout', 'AuthDashboardController@logout');
        });
    });


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
            Route::resource('category', 'CategoryController');
            Route::get('activationCategory/{id}', 'CategoryController@activationCategory');

            //Units
            Route::prefix("units")->group(function () {
                Route::get("", "UnitController@index");
                Route::post("", "UnitController@store");
                Route::put("", "UnitController@update");
                Route::delete("{id}", "UnitController@delete");
            });

            //Cities
            Route::prefix("cities")->group(function () {
                Route::get("", "CityController@index");
                Route::post("", "CityController@store");
                Route::put("", "CityController@update");
            });

            //Areas
            Route::prefix("areas")->group(function () {
                Route::get("", "AreaController@index");
                Route::get("cities", "AreaController@getCities");
                Route::post("", "AreaController@store");
                Route::put("", "AreaController@update");
            });

            //Know us ways
            Route::prefix("know-us-ways")->group(function () {
                Route::get("", "KnowUsWayController@index");
                Route::post("", "KnowUsWayController@store");
                Route::put("", "KnowUsWayController@update");
                Route::delete("{id}", "KnowUsWayController@delete");
            });

            //Newsletters
            Route::prefix("newsletters")->group(function () {
                Route::get("", 'NewsletterController@index');
                Route::get("all", 'NewsletterController@getNewsletters');
            });

            //Offers
            Route::prefix("offers")->group(function () {
                Route::get("", "OfferController@index");
                Route::post("", "OfferController@store");
                Route::put("", "OfferController@update");
                Route::delete("{id}", "OfferController@delete");
                Route::get("toggle-activation/{id}", "OfferController@toggleActivation");
            });

            //Shippings
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
                Route::get("employees", "SupplierController@getAllEmployees");
                Route::get("shippings", "SupplierController@getAllShippings");
                Route::post("", "SupplierController@store");
                Route::put("", "SupplierController@update");
                Route::delete("{id}", "SupplierController@delete");
                Route::get("toggle-activation/{id}", "SupplierController@toggleActivation");
            });

            //Client Groups
            Route::prefix("client-groups")->group(function () {
                Route::get("", "ClientGroupController@index");
                Route::get("clients", "ClientGroupController@getAllClients");
                Route::post("", "ClientGroupController@store");
                Route::put("", "ClientGroupController@update");
            });


            //Client Groups
            Route::prefix("clients")->group(function () {
                Route::get("", "ClientController@index");
                Route::post("", "ClientController@store");
                Route::put("", "ClientController@update");
                Route::get("toggle-activation/{id}", "ClientController@toggleActivation");
            });

            //Client Groups
            Route::prefix("point-groups")->group(function () {
                Route::get("", "ClientController@index");
                Route::post("", "ClientController@store");
                Route::put("", "ClientController@update");
                Route::get("toggle-activation/{id}", "ClientController@toggleActivation");
            });

            //Sales points
            Route::prefix("sales-points")->group(function () {
                Route::get("", "SalePointController@index");
                Route::post("", "SalePointController@store");
                Route::put("", "SalePointController@update");
                Route::get("toggle-activation/{id}", "SalePointController@toggleActivation");
                Route::get("main-categories", "SalePointController@getMainCategories");
                Route::get("clients-groups", "SalePointController@getClientGroups");
            });

            //Sliders
            Route::prefix("sliders")->group(function () {
                Route::get("", "SliderController@index");
                Route::get("products", "SliderController@getProducts");
                Route::post("", "SliderController@store");
                Route::post("update", "SliderController@update");
                Route::delete("{id}", "SliderController@delete");
            });

            //Simple Advertises
            Route::prefix("simple-advertises")->group(function () {
                Route::get("", "SimpleAdvertiseController@index");
                Route::get("products", "SimpleAdvertiseController@getProducts");
                Route::post("", "SimpleAdvertiseController@store");
                Route::post("update", "SimpleAdvertiseController@update");
                Route::delete("{id}", "SimpleAdvertiseController@delete");
            });

            //Deal
            Route::prefix("deal")->group(function () {
                Route::post("", "DealController@insertDeal");
                Route::get("", "DealController@getDeal");
                Route::get("products", "DealController@getProducts");
            });


            Route::prefix("unavailable-cities-clients")->group(function () {
                Route::get("", "UnavailableCityClientController@getUnavailableCitiesClients");
                Route::get("all", "UnavailableCityClientController@getAllUnavailableCitiesClients");
            });

            //start logout
            Route::post('logout', 'AuthDashboardController@logout');
        });
    });
});
