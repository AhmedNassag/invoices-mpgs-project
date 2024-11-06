<?php

use App\Models\Invoice;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Artisan;



Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('migrate');
    return "Cache Cleared Successfully";
});


Route::get('/migrate', function () {
    Artisan::call('migrate');
    return "Migrate Done Successfully";
});

Auth::routes();

Route::group(['middleware' => ['auth'], 'namespace' => 'Admin', 'as' => 'admin.'], function () {

    Route::get('/', 'DashboardController@index')->name('index');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');

    Route::get('profile', 'ProfileController@index')->name('profile.index');
    Route::post('profile', 'ProfileController@update')->name('profile.update');

    Route::resource('invoice', 'InvoiceController');
    Route::get('invoice/{id}/download', 'InvoiceController@download')->name('invoice.download');
    Route::post('invoice/{id}/share', 'InvoiceController@sendInvoice')->name('invoice.share');

    Route::get('invoice/{id}/payments', 'PaymentController@index')->name('invoice.payments');
    Route::post('invoice/{id}/payment', 'PaymentController@paymentCreate')->name('invoice.payment.create');
    Route::get('invoice/{id}/payment/{paymentMethod}', 'PaymentController@payment')->name('invoice.payment');

    Route::resource('quotation', 'QuotationController');
    Route::get('quotation/{id}/download', 'QuotationController@download')->name('quotation.download');
    Route::post('quotation/{id}/share', 'QuotationController@sendQuotation')->name('quotation.share');

    Route::resource('user', 'UserController');
    Route::get('user/role/{id}', 'UserController@getRoleUser')->name('user.role');

    Route::resource('product', 'ProductController')->except(['show']);
    Route::resource('unit', 'UnitController')->except(['show']);

    Route::resource('income', 'IncomeController')->except(['show']);
    Route::get('income/{id}/download', 'IncomeController@download')->name('income.download');

    Route::resource('expense', 'ExpenseController')->except(['show']);
    Route::get('expense/{id}/download', 'ExpenseController@download')->name('expense.download');

    Route::resource('role', 'RoleController')->except(['show']);
    Route::get('permission/{id?}', 'PermissionController@index')->name('permission.index');
    Route::post('permission/{id}', 'PermissionController@savePermission')->name('permission.save');

    Route::resource('tax-rate', 'TaxRateController')->except(['show']);

    Route::get('activity-log', 'ActivityLogController@index')->name('activity-log.index');
    Route::get('activity-log/clear', 'ActivityLogController@clear')->name('activity-log.clear');
    Route::get('activity-log/{id}', 'ActivityLogController@show')->name('activity-log.show');

    Route::get('setting', 'SettingController@index')->name('setting.index');
    Route::post('setting', 'SettingController@store')->name('setting.store');
    Route::post('setting-invoice', 'SettingController@setInvoice')->name('setting.invoice.save');

    Route::get('notification', 'NotificationController@index')->name('notification.index');
    Route::get('notification/{id}', 'NotificationController@show')->name('notification.show');


    Route::get('barcode', 'BarcodeController@index')->name('barcode.index');
    Route::post('barcode', 'BarcodeController@index')->name('barcode.filter');
    Route::get('barcode/pdf/{productId}/{quantity}', 'BarcodeController@pdf')->name('barcode.pdf');
});


Route::group(['middleware' => ['auth'], 'namespace' => 'Report', 'as' => 'admin.'], function () {
    Route::get('invoice-overview-report', 'InvoiceOverviewReportController@index')->name('invoice.overview.report.index');
    Route::post('invoice-overview-report', 'InvoiceOverviewReportController@index')->name('invoice.overview.report.filter');
    Route::get('invoice-overview-report/pdf/{userId}/{fromData}/{toDate}', 'InvoiceOverviewReportController@pdf')->name('invoice.overview.report.pdf');
});

Route::group(['namespace' => 'Admin'], function () {
    Route::get('invoice/{id}/share', 'InvoiceController@share')->name('invoice.share');
    Route::get('quotation/{id}/share', 'QuotationController@share')->name('quotation.share');
});


Route::group(['prefix'=> 'ajax', 'as' => 'ajax.'], function () {
    Route::post('product-list', 'UtilityController@getProductList')->name('product.list');
    Route::post('tax-list', 'UtilityController@getTaxList')->name('tax.list');
    Route::post('quick-user', 'UtilityController@addQuickUser')->name('quick.user');
});

Route::fallback(function () {
    return view('errors.404');
});
