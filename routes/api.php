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

// Registration, Validation and Forgot Password
Route::name('login')->post('/login', 'AuthController@login');
Route::name('registration')->post('/register', 'AccountController@register');
Route::name('check-verification-code')->get('/check-verification-code', 'AccountController@checkVerificationCode');
Route::name('update-registration')->post('/verification', 'AccountController@setPassword');
Route::name('registration-country')->get('/registration-country-list', 'CountryController@getListCountry');
Route::name('registration-email-validation')->post('/registration-email-validation', 'MailgunController@send_validation');
Route::name('registration-get-info')->get('/registration-get-info','AccountController@getInfo');
Route::name('registration-get-update-info')->post('/registration-get-update-info','AccountController@updateRegistrationAccount');
Route::name('forgot-password')->post('/forgot-password','AccountController@forgotPassword');
Route::name('check-reset-password-token')->post('/validate-reset-password-token', 'AccountController@validateResetPasswordToken');
Route::name('reset-password')->post('/reset-password', 'AccountController@resetPassword');

Route::middleware('auth:api')->group(function () {

    //User
    Route::resource('users', 'UserController');
    Route::name('current-user')->get('current-user', 'UserController@currentInforUser');
    Route::name('check-admin-user')->get('/is_admin', 'UserController@checkUserAdmin');
    Route::name('get-type')->get('/user/type', 'UserController@getTypes');
    Route::name('get-payment-list')->get('payment-list', 'UserController@getPaymentList');
    Route::name('update-user')->put('/admin/update-user', 'AuthController@edit');

    //Article
    Route::name('get-backlinks-list')->get('backlinks-list', 'ArticlesController@getList');
    Route::name('get-article-list')->get('article-list', 'ArticlesController@getArticleList');
    Route::name('get-writer-list')->get('writer-list', 'ArticlesController@getWriterList');
    Route::name('add-articles')->post('articles', 'ArticlesController@store');
    Route::name('update-article-content')->post('articles-content', 'ArticlesController@updateContent');
    Route::name('get-article-list-admin')->get('article-list-admin', 'ArticlesController@getArticleListAdmin');
    Route::name('delete-article')->post('delete-article', 'ArticlesController@deleteArticle');

    //Billing
    Route::name('get-buyer-billing')->get('buyer-billing', 'BuyerBillingController@getList');
    Route::name('get-seller-billing')->get('seller-billing', 'SellerBillingController@getList');
    Route::name('pay-seller-billing')->post('seller-billing', 'SellerBillingController@payBilling');
    Route::name('get-writer-billing')->get('writer-billing', 'WriterBillingController@getList');
    Route::name('pay-writer-billing')->post('writer-billing', 'WriterBillingController@payBilling');
    Route::name('get-seller-info')->post('get-seller-info', 'SellerBillingController@getSellerInfo');
    Route::name('get-writer-info')->post('get-writer-info', 'WriterBillingController@getWriterInfo');

    //Wallet Transaction
    Route::name('get-wallet-transaction')->get('wallet-transaction', 'WalletTransactionController@getList');
    Route::name('get-user-buyer')->get('wallet-user-buyer', 'WalletTransactionController@getListBuyer');
    Route::name('get-user-seller')->get('wallet-user-seller', 'WalletTransactionController@getListSeller');
    Route::name('get-user-seller-team')->get('wallet-user-seller-team', 'WalletTransactionController@getListSellerTeam');
    Route::name('create-wallet')->post('add-wallet', 'WalletTransactionController@addWallet');
    Route::name('update-wallet')->post('update-wallet', 'WalletTransactionController@updateWallet');

    //Buy
    Route::name('get-buy')->get('buy','BuyController@getList');
    Route::name('get-check-credit-auth')->get('check-credit-auth','BuyController@checkCreditAuth');
    Route::name('update-buy')->put('buy','BuyController@update');
    Route::name('update-buy-dislike')->post('buy-dislike','BuyController@updateDislike');
    Route::name('update-buy-like')->post('buy-like','BuyController@updateLike');

    //Accounts
    Route::name('add-accounts')->post('accounts', 'AccountController@store');
    Route::name('get-accounts')->get('accounts', 'AccountController@getList');
    Route::name('update-accounts')->put('accounts', 'AccountController@edit');
    Route::name('get-seller')->get('accounts/get-sellers', 'AccountController@getUserRole');
    Route::name('get-user-wallet-credit')->get('wallet-credit', 'AccountController@getWalletCredit');
    Route::name('get-team-in-charge')->get('team-in-charge', 'AccountController@getTeamInCharge');
    Route::name('get-team-in-charge-per-role')->get('team-in-charge-per-role', 'AccountController@getTeamInChargePerRole');
    Route::name('get-buyer-commission')->get('buyer-commission', 'AccountController@getBuyerCommission');
    Route::name('add-sub-account')->post('sub-account', 'AccountController@storeSubAccount');
    Route::name('get-sub-account')->get('/sub-account', 'AccountController@getSubAccount');
    Route::name('delete-sub-account')->get('/delete-sub-account', 'AccountController@deleteSubAccount');
    Route::name('update-sub-account')->get('/update-sub-account', 'AccountController@updateSubAccount');
    Route::name('get-verified-account')->get('/get-verified-account', 'AccountController@checkVerifiedAccount');
    Route::name('verify-account')->post('/verify-account', 'AccountController@verifyAccount');

    //Purchase
    Route::name('get-purchase')->get('purchase', 'PurchaseController@getList');

    //Dashboard Admin
    Route::name('dashboard-admin')->get('/dashboard-admin', 'DashboardAdminController@index');

    //Dashboard
    Route::name('ext-reports')->post('/ext/reports', 'ExtDomainController@reports');
    Route::name('int-reports')->post('/int/reports', 'IntDomainController@reports');
    Route::name('baclink-reports')->post('/backlink/reports', 'BackLinkController@reports');
    Route::name('baclink-reports-price')->post('/backlink/reports-price', 'BackLinkController@reportsPrice');
    Route::name('dashboard')->get('/dashboard', 'DashboardController@index');

    //URL propect list page
    Route::name('get-list-url-propect')->get('/url-prospect', 'UrlProspectController@getList');

    //Publisher URL list page
    Route::name('get-publisher-info')->get('/get-publisher-info', 'PublisherController@getInfo');
    Route::name('publisher-create')->post('/publisher', 'PublisherController@store');
    Route::name('publisher-get')->get('/publisher', 'PublisherController@getList');
    Route::name('publisher-update')->put('/publisher', 'PublisherController@update');
    Route::name('publisher-update-multiple')->post('/update-multiple-publisher', 'PublisherController@updateMultiple');
    Route::name('publisher-delete')->delete('/publisher', 'PublisherController@delete');
    Route::name('publisher-get-ahrefs')->put('/publisher/ahrefs', 'PublisherController@getAhrefs');
    Route::name('upload-csv')->post('/publisher/upload-csv', 'PublisherController@importExcel');
    Route::name('publisher-get-summary')->get('/publisher/summary', 'PublisherController@getSummary');
    Route::name('publisher-valid')->post('/publisher/valid', 'PublisherController@validData');
    Route::name('best-price-log')->get('publisher/best-prices/log', 'PublisherController@bestPricesGenerationLog');
    Route::name('seller-incharge')->get('/seller-incharge/{user_id}', 'PublisherController@getListSellerIncharge');
    Route::name('publisher-qc-validation')->post('/publisher/qc-validation', 'PublisherController@qcValidationUpdate');
    Route::name('publisher-domain-zones')->get('/publisher/domain-zones', 'PublisherController@getDomainZoneExtensions');

    //External Domain List Page
    Route::name('ext-get-alexa')->post('/ext/alexa', 'ExtDomainController@getAlexaLink');
    Route::name('ext-get-ahrefs')->put('/ext/ahrefs', 'ExtDomainController@getAhrefs');
    Route::name('ext-get')->get('/ext', 'ExtDomainController@getList');
    Route::name('ext-create')->post('/ext', 'ExtDomainController@store');
    Route::name('ext-update-status')->put('/ext/status', 'ExtDomainController@updateStatus');
    Route::name('ext-update')->put('/ext', 'ExtDomainController@update');
    Route::name('ext-get-contacts')->post('/ext/get-contacts', 'ExtDomainController@crawlContact');
    Route::name('ext-upload-csv')->post('/ext/upload-csv', 'ExtDomainController@importExcel');
    Route::name('ext-delete')->delete('/ext', 'ExtDomainController@delete');
    Route::name('ext-update-multiple-status')->put('/ext/update-multiple-status', 'ExtDomainController@updateMultipleStatus');
    Route::name('ext-get-ext-seller')->get('ext/ext-seller','ExtDomainController@getListExtSeller');
    Route::name('ext-update-multiple-employee')->post('/update-multiple-employee','ExtDomainController@updateMultipleEmployee');

    //Internal Page
    Route::name('int-get')->get('/int', 'IntDomainController@getList');
    Route::name('int-create')->post('/int', 'IntDomainController@store');
    Route::name('int-update')->put('/int', 'IntDomainController@update');

    // Incomes
    Route::name('get-incomes')->get('incomes', 'IncomesController@getList');
    Route::name('update-incomes')->put('incomes', 'IncomesController@update');

    // Incomes Admin
    Route::name('get-incomes-admin')->get('incomes-admin', 'IncomesAdminController@getList');

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
    Route::name('get-list-country')->get('/country-list', 'CountryController@getListCountry');
    Route::name('get-list-languages')->get('/languages-list', 'CountryController@getListLanguages');
    Route::name('get-countries-int')->get('/countries-int', 'UserController@getCountriesIntDomain');
    Route::name('get-mail-template')->get('/mails', 'MailController@getList');
    Route::name('send-mail')->post('/send-mails', 'MailController@sendEmails');
    Route::name('add-mail-template')->post('/mails', 'MailController@store');
    Route::name('update-mail-template')->put('/mails', 'MailController@edit');
    Route::name('delete-mail-template')->delete('/mails', 'MailController@delete');
    // Route::name('get-mail-logs')->get('/mail-logs', 'LogController@getMailList');

    Route::middleware(['check_admin'])->prefix('admin')->group(function() {
        Route::name('add-user')->post('/add-user', 'AuthController@register');
        //Route::name('update-user')->put('/update-user', 'AuthController@edit');
        Route::name('update-user-permission')->put('/update-user-permission', 'UserController@editPermissions');
        Route::name('update-user-int-permission')->put('/update-user-int-permission', 'UserController@editIntPermissions');
        Route::name('get-logs')->get('/logs', 'LogController@getList');
        Route::name('get-log-tables')->get('/logs/tables', 'LogController@getTables');
        Route::name('get-log-actions')->get('/logs/actions', 'LogController@getActions');
        Route::name('add-country')->post('/countries', 'CountryController@store');
        Route::name('add-language')->post('/language', 'ConfigController@storeLanguage');
        Route::name('get-language')->get('/language', 'ConfigController@getLanguage');
        Route::name('update-language')->put('/language', 'ConfigController@updateLanguage');
        Route::name('update-country')->put('/countries', 'CountryController@edit');
        Route::name('get-configs')->get('/configs', 'ConfigController@getList');
        Route::name('update-configs')->put('/configs', 'ConfigController@edit');
        Route::name('get-payment')->get('/payments', 'PaymentController@getList');
        Route::name('add-payment')->post('/payments', 'PaymentController@store');
        Route::name('update-payment')->put('/payments', 'PaymentController@edit');
    });

    //Formula
    Route::name('get-formula')->get('/formula', 'ConfigController@getFormula');
    Route::name('update-formula')->post('/formula', 'ConfigController@updateFormula');

    // Ahref Subscription
    Route::name('subscription-info')->get('subscription-info', 'ConfigController@getSubscriptionInfo');

    // Auth routes.
    Route::name('logout')->post('/logout', 'AuthController@logout');

    //Mailgun
    Route::group(['prefix'=> 'mail'], function(){
        Route::name('email_send')->post('/send','MailgunController@send');
        Route::name('email_retrieve_all')->get('/retrieve-all','MailgunController@retrieve_all');
        Route::name('filter_recipient')->get('/filter-recipient','MailgunController@recipient_filter');
        Route::name('get_sent')->post('/sent','MailgunController@sent');
        Route::name('starred')->get('/starred','MailgunController@starred');
        Route::name('starred-thread')->post('/starred-thread','MailgunController@starredThread');
        Route::name('is_viewed')->get('/is-viewed','MailgunController@setViewMessage');
        Route::name('is_viewed_thread')->post('/is-viewed-thread','MailgunController@setViewMessageThread');
        Route::name('labeling')->post('/labeling','MailgunController@labeling');
        Route::name('labeling-thread')->post('/labeling-thread','MailgunController@labelingThread');
        Route::name('delete-message')->get('/delete-message','MailgunController@deleteMessage');
        Route::name('delete-message-thread')->post('/delete-message-thread','MailgunController@deleteMessageThread');
        Route::name('get_replies')->post('/get-reply','MailgunController@get_reply');
        Route::name('mail-logs')->get('/mail-logs','MailgunController@mail_logs');
        Route::name('get-user-email-list')->get('/user-email-list','AccountController@userEmailFilter');
        Route::name('get-mail-list')->get('/get-mail-list','MailgunController@get_mail_list');
    });

    Route::name('labels')->resource('/label','LabelController');
    Route::name('labels')->get('/label_list/{email}','LabelController@getLabels');

     //Notifications
    Route::name('notifications.get')->get('/notifications/{user_id}', 'NotificationController@getUserNotifications');
    Route::name('notifications.seen')->put('/notifications/{user_id}', 'NotificationController@setUserNotificationsSeen');

    //Continents
    Route::name('get-list-continents')->get('/continent-list', 'ContinentController@getListContinent');

    //Compute for best price
    Route::name('generate-best-price')->post('generate-best-price', 'PublisherController@generateBestPrice');

    //Graphs
    Route::name('orders-graph')->get('/graphs/orders', 'GraphsController@getOrdersGraph');
    Route::name('seller-valid-graph')->get('/graphs/seller-valid', 'GraphsController@getSellerValidGraph');
    Route::name('url-valid-graph')->get('/graphs/url-valid', 'GraphsController@getUrlValidGraph');
    Route::name('url-valid-graph')->get('/graphs/url-valid-price', 'GraphsController@getUrlValidPriceGraph');
});

//Mailgun external
Route::name('post_replies')->post('/mail/post-reply','MailgunController@post_reply');
Route::name('/check_domain')->post('/check-domain','MailgunController@check_domain');
Route::name('domain-status')->get('/mail/status','MailgunController@status_mail');
Route::name('message_view')->post('/mail/view-message','MailgunController@view_message');
Route::name('show_attach')->post('/mail/show-attachment','MailgunController@show_attachment');

//test pusher
Route::name('pusher')->get('/test-pusher','PushController@test');

//test paypal payment
Route::get('payment', 'PayPalController@payment')->name('payment');
Route::get('cancel', 'PayPalController@cancel')->name('payment.cancel');
Route::get('payment/success', 'PayPalController@success')->name('payment.success');

Route::get('url-prospect-email-extraction', 'ConfigController@urlProspectEmailExtraction')->name('url-prospect-email-extraction');
