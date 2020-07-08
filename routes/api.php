<?php

use Illuminate\Http\Request;

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

Route::name('login')->post('/login', 'AuthController@login');
Route::name('registration')->post('/register', 'AccountController@register');
Route::name('check-verification-code')->get('/check-verification-code', 'AccountController@checkVerificationCode');
Route::name('update-registration')->post('/verification', 'AccountController@setPassword');

Route::middleware('auth:api')->group(function () {

    //User
    Route::resource('users', 'UserController');
    Route::name('current-user')->get('current-user', 'UserController@currentInforUser');
    Route::name('check-admin-user')->get('/is_admin', 'UserController@checkUserAdmin');
    Route::name('get-type')->get('/user/type', 'UserController@getTypes');
    Route::name('get-payment-list')->get('payment-list', 'UserController@getPaymentList');
    Route::name('update-user')->put('/admin/update-user', 'AuthController@edit');

    //Article
    Route::name('get-article')->get('articles', 'ArticlesController@getList');

    //Billing
    Route::name('get-seller-billing')->get('seller-billing', 'SellerBillingController@getList');
    Route::name('pay-seller-billing')->post('seller-billing', 'SellerBillingController@payBilling');

    //Wallet Transaction
    Route::name('get-wallet-transaction')->get('wallet-transaction', 'WalletTransactionController@getList');
    Route::name('get-user-buyer')->get('wallet-user-buyer', 'WalletTransactionController@getListBuyer');
    Route::name('create-wallet')->post('add-wallet', 'WalletTransactionController@addWallet');
    Route::name('update-wallet')->post('update-wallet', 'WalletTransactionController@updateWallet');

    //Buy
    Route::name('get-buy')->get('buy','BuyController@getList');
    Route::name('update-buy')->put('buy','BuyController@update');
    Route::name('update-buy-dislike')->post('buy-dislike','BuyController@updateDislike');
    Route::name('update-buy-like')->post('buy-like','BuyController@updateLike');

    //Accounts
    Route::name('add-accounts')->post('accounts', 'AccountController@store');
    Route::name('get-accounts')->get('accounts', 'AccountController@getList');
    Route::name('update-accounts')->put('accounts', 'AccountController@edit');

    //Purchase
    Route::name('get-purchase')->get('purchase', 'PurchaseController@getList');

    //Dashboard
    Route::name('ext-reports')->post('/ext/reports', 'ExtDomainController@reports');
    Route::name('int-reports')->post('/int/reports', 'IntDomainController@reports');
    Route::name('baclink-reports')->post('/backlink/reports', 'BackLinkController@reports');
    Route::name('baclink-reports-price')->post('/backlink/reports-price', 'BackLinkController@reportsPrice');

    //Publisher URL list page
    Route::name('publisher-get')->get('/publisher', 'PublisherController@getList');
    Route::name('publisher-update')->put('/publisher', 'PublisherController@update');
    Route::name('publisher-delete')->delete('/publisher', 'PublisherController@delete');
    Route::name('publisher-get-ahrefs')->get('/publisher/ahrefs', 'PublisherController@getAhrefs');
    Route::name('upload-csv')->post('/publisher/upload-csv', 'PublisherController@importExcel');

    //External Page
    Route::name('ext-get-alexa')->post('/ext/alexa', 'ExtDomainController@getAlexaLink');
    Route::name('ext-get-ahrefs')->get('/ext/ahrefs', 'ExtDomainController@getAhrefs');
    Route::name('ext-get')->get('/ext', 'ExtDomainController@getList');
    Route::name('ext-create')->post('/ext', 'ExtDomainController@store');
    Route::name('ext-update-status')->put('/ext/status', 'ExtDomainController@updateStatus');
    Route::name('ext-update')->put('/ext', 'ExtDomainController@update');
    Route::name('ext-get-contacts')->get('/ext/get-contacts', 'ExtDomainController@crawlContact');

    //Internal Page
    Route::name('int-get')->get('/int', 'IntDomainController@getList');
    Route::name('int-create')->post('/int', 'IntDomainController@store');
    Route::name('int-update')->put('/int', 'IntDomainController@update');

    // Incomes
    Route::name('get-incomes')->get('incomes', 'IncomesController@getList');
    Route::name('update-incomes')->put('incomes', 'IncomesController@update');

    // Followup Sales
    Route::name('get-sales')->get('sales', 'FollowupSalesController@getList');
    Route::name('update-sales')->put('sales', 'FollowupSalesController@update');

    //Backlink
    Route::resource('backlinks', 'BackLinkController');
    Route::name('delete-backlinks')->post('delete-backlinks', 'BackLinkController@deleteBacklinks');

    //Intdomain
    Route::resource('intdomains', 'IntDomainController')->only(['index', 'show']);
    Route::name('list-by-hosting')->get('/list-by-hosting/{id}', 'IntDomainController@getIntByHosting');
    Route::name('list-by-domain')->get('/list-by-domain/{id}', 'IntDomainController@getIntByDomain');

    //Hosting
    Route::resource('hostings', 'HostingController');

    //Domain provider
    Route::resource('domains', 'DomainProviderController');

    //Role
    Route::name('role-get')->get('/roles', 'RoleController@getRoles');
    Route::name('get-countries')->get('/countries', 'CountryController@getCountries');
    Route::name('get-countries-int')->get('/countries-int', 'UserController@getCountriesIntDomain');
    Route::name('get-mail-template')->get('/mails', 'MailController@getList');
    Route::name('send-mail')->post('/send-mails', 'MailController@sendEmails');
    Route::name('add-mail-template')->post('/mails', 'MailController@store');
    Route::name('update-mail-template')->put('/mails', 'MailController@edit');
    Route::name('delete-mail-template')->delete('/mails', 'MailController@delete');
    Route::name('get-mail-logs')->get('/mail-logs', 'LogController@getMailList');

    Route::middleware(['check_admin'])->prefix('admin')->group(function() {
        Route::name('add-user')->post('/add-user', 'AuthController@register');
        //Route::name('update-user')->put('/update-user', 'AuthController@edit');
        Route::name('update-user-permission')->put('/update-user-permission', 'UserController@editPermissions');
        Route::name('update-user-int-permission')->put('/update-user-int-permission', 'UserController@editIntPermissions');
        Route::name('get-logs')->get('/logs', 'LogController@getList');
        Route::name('get-log-tables')->get('/logs/tables', 'LogController@getTables');
        Route::name('get-log-actions')->get('/logs/actions', 'LogController@getActions');
        Route::name('add-country')->post('/countries', 'CountryController@store');
        Route::name('update-country')->put('/countries', 'CountryController@edit');
        Route::name('get-configs')->get('/configs', 'ConfigController@getList');
        Route::name('update-configs')->put('/configs', 'ConfigController@edit');
        Route::name('get-payment')->get('/payments', 'PaymentController@getList');
        Route::name('add-payment')->post('/payments', 'PaymentController@store');
        Route::name('update-payment')->put('/payments', 'PaymentController@edit');
    });

    // Auth routes.
    Route::name('logout')->post('/logout', 'AuthController@logout');
});
