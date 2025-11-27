<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CustomersController;
use App\Http\Controllers\UserController;
use App\Http\Livewire\Admin\CustomerSearch;
use App\Http\Livewire\Admin\PaymentIndex;
use App\Http\Livewire\Admin\ProductCreate;
use App\Http\Livewire\Admin\ProductEdit;
use App\Http\Livewire\Admin\ProductIndex;
use App\Http\Livewire\Admin\SalesIndex;
use App\Http\Livewire\Admin\SmsTemplateEdit;
use App\Http\Livewire\Admin\SmsTemplateIndex;
use App\Http\Livewire\ListOfInactiveCustomer;
use App\Http\Livewire\User\UserPaymentIndex;
use App\Http\Livewire\User\UserSalesIndex;
use Illuminate\Support\Facades\Route;

\Illuminate\Support\Facades\Auth::routes(['register' => false]);
Route::get('password/otp/{identifier}', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp');
Route::post('password/otp/{identifier}', [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp-verify');

Route::get('/', fn () => redirect()->route('login'))->name('/');

Route::middleware(['auth'])->group(function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.change');
    Route::post('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('print/card/{customer}', [PrintController::class, 'printCard'])->name('print.card');
    Route::get('print/customer', [PrintController::class, 'customerListPrint'])->name('print.customer-list');
    Route::get('print/customer/inactive', [PrintController::class, 'printInactiveCustomerList'])->name('print.customer-list.inactive');
    Route::get('print/sales/{user}', [PrintController::class, 'salesListPrint'])->name('print.sales-list');

    Route::prefix('admin')->as('admin.')->middleware(['admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        Route::prefix('settings')->as('settings.')->group(function() {
            Route::get('general', [SettingController::class, 'general'])->name('general');
            Route::post('general', [SettingController::class, 'storeGeneral'])->name('general.store');
            Route::get('logo-favicon', [SettingController::class, 'logoFavicon'])->name('logo-favicon');
            Route::post('logo-favicon', [SettingController::class, 'storeLogoFavicon'])->name('logo-favicon.store');
        });

        Route::resource('employee', EmployeeController::class);

        Route::get('customer/search', CustomerSearch::class)->name('customer.search');
        Route::get('customer/pending', [CustomerController::class, 'pending'])->name('customer.pending');
        Route::get('customer/rejected', [CustomerController::class, 'rejected'])->name('customer.rejected');
        Route::get('customer/request', [CustomerController::class, 'editRequest'])->name('customer.request');
        Route::get('customer/inactive', ListOfInactiveCustomer::class)->name('customer.inactive');

        Route::resource('customer', CustomerController::class);

        Route::prefix('product')->as('product.')->group(function () {
            Route::get('/', ProductIndex::class)->name('index');
            Route::get('create', ProductCreate::class)->name('create');
            Route::get('{product}/edit', ProductEdit::class)->name('edit');
        });

        Route::group(['prefix' => 'sales', 'as' => 'sales.'], function () {
            Route::get('/', SalesIndex::class)->name('index');
        });

        Route::group(['prefix' => 'payments', 'as' => 'payments.'], function () {
            Route::get('/', PaymentIndex::class)->name('index');
        });

        Route::get('sms-template', SmsTemplateIndex::class)->name('sms-template');
        Route::get('sms-template/{template}', SmsTemplateEdit::class)->name('sms-template.edit');

        Route::get('money/details', [AdminController::class, 'moneyDetails'])->name('money.details');

        Route::get('migrate/upgrade', [AdminController::class, 'migrateUpgrade']);
    });

    Route::prefix('user')->middleware(['user'])->as('user.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');

        Route::get('customer/search', CustomerSearch::class)->name('customer.search');
        Route::resource('customer', CustomersController::class);

        Route::group(['prefix' => 'sales', 'as' => 'sales.'], function () {
            Route::get('/', UserSalesIndex::class)->name('index');
        });

        Route::group(['prefix' => 'payments', 'as' => 'payments.'], function () {
            Route::get('/', UserPaymentIndex::class)->name('index');
        });

        Route::get('money/details', [UserController::class, 'moneyDetails'])->name('money.details');
    });

    Route::post('stop-impersonate', [LoginController::class, 'stopImpersonate'])->name('stop-impersonate');
});

