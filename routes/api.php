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
Route::name('get-payment-list-registration')->get('/payment-list-registration', 'AccountController@getPaymentList');
Route::name('get-payment-list-registration')->get('/payment-list-registration', 'AccountController@getPaymentList');
Route::name('order-confirmation-get-info')->get('/order-confirmation-get-info', 'FollowupSalesController@orderConfirmation');
Route::name('cancel-order-confirmation-get-info')->get('/cancel-order-confirmation-get-info', 'FollowupSalesController@CancelOrderConfirmation');
Route::name('registration-languages')->get('/registration-languages-list', 'CountryController@getListLanguages');

//survey
// Route::name('store-survey-code')->post('/survey-code', 'SurveyController@store');
Route::name('store-survey-code')->post('/survey-code', 'SurveyTwoController@store');
// Route::name('check-survey-code')->post('/survey/check-survey-code', 'SurveyController@checkSurveyCode');
Route::name('check-survey-code')->post('/survey/check-survey-code', 'SurveyTwoController@checkSurveyCode');
// Route::name('check-survey-code-set')->post('/survey/check-survey-code-set', 'SurveyController@hasUserAnsweredSurveySet');
Route::name('check-survey-code-set')->post('/survey/check-survey-code-set', 'SurveyTwoController@hasUserAnsweredSurveySet');

//subscription
Route::name('subscribe-user')->post('/subscription/subscribe-user', 'UserController@subscribeUser');
Route::name('check-subscription-code')->post('/subscription/check-subscription-code', 'UserController@checkSubscriptionCode');
Route::name('check-subscribed')->post('/subscription/check-subscribed', 'UserController@hasUserSubscribed');

Route::middleware('auth:api')->group(function () {

    //Profile
    Route::name('add-affiliate-code')->post('/profile/add-affiliate-code', 'AccountController@saveAffiliateCode');
    Route::name('affiliate-buyers')->get('/profile/affiliate-buyers', 'AccountController@getAffiliateBuyers');

    //Surveys
    Route::name('get-survey-list')->get('/surveys', 'SurveyController@getList');
    Route::name('get-survey-list-two')->get('/surveys-two', 'SurveyTwoController@getList');
    // Route::name('store-survey')->post('survey', 'SurveyController@store');
    Route::name('store-survey')->post('survey', 'SurveyTwoController@store');
    Route::name('survey-last-set')->get('/survey/last-set', 'SurveyController@getLastSurveySet');
    // Route::name('check-user-answered-survey')->get('/survey/check-both', 'SurveyController@hasUserAnsweredBothSurveys');
    Route::name('check-user-answered-survey')->get('/survey/check-both', 'SurveyTwoController@hasUserAnsweredBothSurveys');
    // Route::name('check-survey-set')->post('/survey/check-survey-set', 'SurveyController@hasUserAnsweredSurveySet');
    Route::name('check-survey-set')->post('/survey/check-survey-set', 'SurveyTwoController@hasUserAnsweredSurveySet');
    Route::name('survey-question-full-details')->get('/survey/survey-question-full-details', 'SurveyController@getSurveyQuestionFullDetails');
    Route::name('survey-question-full-details-two')->get('/survey/survey-question-full-details-two', 'SurveyTwoController@getSurveyQuestionFullDetails');

    //Seller Surveys
    // Route::name('check-seller-answered-survey')->post('/survey/seller/check', 'SurveyController@hasSellerAnsweredSurvey');
    Route::name('check-seller-answered-survey')->post('/survey/seller/check', 'SurveyTwoController@hasSellerAnsweredSurvey');

    //Writer Surveys
    // Route::name('check-writer-answered-survey')->post('/survey/writer/check', 'SurveyController@hasWriterAnsweredSurvey');
    Route::name('check-writer-answered-survey')->post('/survey/writer/check', 'SurveyTwoController@hasWriterAnsweredSurvey');

    //User
    Route::resource('users', 'UserController');
    Route::name('current-user')->get('current-user', 'UserController@currentInforUser');
    Route::name('check-admin-user')->get('/is_admin', 'UserController@checkUserAdmin');
    Route::name('get-type')->get('/user/type', 'UserController@getTypes');
    Route::name('get-payment-list')->get('payment-list', 'UserController@getPaymentList');
    Route::name('update-user')->put('/admin/update-user', 'AuthController@edit');
    Route::name('upload-avatar')->post('/user-upload-avatar', 'UserController@uploadAvatar');
    Route::name('get-unread-emails')->get('/get-unread-emails/{email}', 'UserController@getUnreadEmails');
    Route::name('get-unread-emails-list')->get('/get-unread-emails-list/{email}/{all}', 'UserController@getUnreadEmailList');
    Route::name('get-user-drafts')->get('/get-user-drafts', 'UserController@getUserDrafts');
    Route::name('update-user-payment-type-image')->post('/admin/update-user-payment-type-image', 'AuthController@updateUserPaymentTypeImage');

    //setup chatgpt
    Route::name('add-prompt')->post('add-prompt', 'ChatGptSetupController@addPrompt');
    Route::name('generate-gpt')->post('generate-gpt', 'ChatGptSetupController@generateGpt');
    Route::name('update-prompt')->post('update-prompt', 'ChatGptSetupController@updatePrompt');
    Route::name('setup-chat-gpt')->get('setup-chat-gpt', 'ChatGptSetupController@index');
    Route::name('delete-prompt')->get('delete-prompt', 'ChatGptSetupController@deletePrompt');
    Route::name('edit-prompt')->get('edit-prompt/{id}/edit', 'ChatGptSetupController@edit');
    Route::name('show-prompt')->get('show-prompt', 'ChatGptSetupController@showPrompt');

    //Article
    Route::name('get-backlinks-list')->get('backlinks-list', 'ArticlesController@getList');
    Route::name('get-article-list')->get('article-list', 'ArticlesController@getArticleList');
    Route::name('get-writer-list')->get('writer-list', 'ArticlesController@getWriterList');
    Route::name('get-valid-external-writers')->get('valid-external-writers', 'ArticlesController@getValidExternalWriters');
    Route::name('add-articles')->post('articles', 'ArticlesController@store');
    Route::name('update-article-content')->post('articles-content', 'ArticlesController@updateContent');
    Route::name('get-article-list-admin')->get('article-list-admin', 'ArticlesController@getArticleListAdmin');
    Route::name('delete-article')->post('delete-article', 'ArticlesController@deleteArticle');
    Route::name('accept-decline-article')->post('accept-decline-article', 'ArticlesController@acceptDeclineArticle');
    Route::name('confirm-article')->post('confirm-article', 'ArticlesController@confirmArticle');
    Route::name('delete-article-internal')->post('delete-article-internal', 'ArticlesController@deleteArticleInternal');

    //Billing
    Route::name('get-injection-billing')->get('injection-billing', 'InjectionBillingController@getList');
    Route::name('get-injection-billing-seller')->get('injection-billing-seller', 'InjectionBillingController@sellerListInjection');
    Route::name('get-injection-billing-seller-info')->get('injection-billing-seller-info', 'InjectionBillingController@sellerInfoInjection');
    Route::name('pay-seller-billing-injection')->post('pay-seller-billing-injection', 'InjectionBillingController@paySellerInjection');
    Route::name('get-buyer-billing')->get('buyer-billing', 'BuyerBillingController@getList');
    Route::name('get-seller-billing')->get('seller-billing', 'SellerBillingController@getList');
    Route::name('pay-seller-billing')->post('seller-billing', 'SellerBillingController@payBilling');
    Route::name('reupload-seller-billing-doc')->post('seller-billing-reupload-doc', 'SellerBillingController@sellerBillingReuploadDoc');
    Route::name('update-seller-billing')->put('seller-billing/update/multiple', 'SellerBillingController@updateBillings');
    Route::name('get-writer-billing')->get('writer-billing', 'WriterBillingController@getList');
    Route::name('pay-writer-billing')->post('writer-billing', 'WriterBillingController@payBilling');
    Route::name('reupload-writer-billing-doc')->post('writer-billing-reupload-doc', 'WriterBillingController@writerBillingReuploadDoc');
    Route::name('get-seller-info')->post('get-seller-info', 'SellerBillingController@getSellerInfo');
    Route::name('get-writer-info')->post('get-writer-info', 'WriterBillingController@getWriterInfo');

    //Wallet Transaction
    Route::name('get-wallet-transaction')->get('wallet-transaction', 'WalletTransactionController@getList');
    Route::name('get-user-buyer')->get('wallet-user-buyer', 'WalletTransactionController@getListBuyer');
    Route::name('get-user-seller')->get('wallet-user-seller', 'WalletTransactionController@getListSeller');
    Route::name('get-user-seller-team')->get('wallet-user-seller-team', 'WalletTransactionController@getListSellerTeam');
    Route::name('create-wallet')->post('add-wallet', 'WalletTransactionController@addWallet');
    Route::name('refund-wallet')->post('refund-wallet', 'WalletTransactionController@refundWallet');
    Route::name('update-wallet')->post('update-wallet', 'WalletTransactionController@updateWallet');
    Route::name('check-on-process-refund')->get('check-on-process-refund', 'WalletTransactionController@checkOnProcessRefund');
    Route::name('get-user-buyer-with-wallet')->get('wallet-user-buyer-transactions', 'WalletTransactionController@getListBuyerWithWalletTransaction');
    Route::name('get-payment-image')->get('/payments/image', 'PaymentController@getPaymentTypeImageList');

    //Wallet Summary
    Route::name('get-wallet-summary')->get('wallet-summary', 'WalletSummaryController@getList');

    //Backlinks Prospect
    Route::name('get-backlink-prospect')->get('backlink-prospect', 'BacklinkProspectController@getList');
    Route::name('fetch-backlink-prospects-from-apacaff')->get('fetch-backlink-prospects-from-apacaff', 'BacklinkProspectController@fetchBacklinkProspect');
    Route::name('get-backlink-prospect-ahrefs')->post('backlink-prospect/ahrefs', 'BacklinkProspectController@getAhrefs');
    Route::name('backlink-prospect-upload-csv')->post('backlink-prospect-upload-csv', 'BacklinkProspectController@importCsv');
    Route::name('backlink-prospect-delete')->post('backlink-prospect-delete', 'BacklinkProspectController@delete');
    Route::name('backlink-prospect-edit')->post('backlink-prospect-edit', 'BacklinkProspectController@editMultiple');
    Route::name('backlink-prospect-move')->post('backlink-prospect-move', 'BacklinkProspectController@moveToUrlProspect');
    Route::name('backlink-prospect-update')->post('backlink-prospect-update', 'BacklinkProspectController@update');
    Route::name('backlink-prospect-totals')->get('backlink-prospect-totals', 'BacklinkProspectController@getTotals');
    Route::name('backlink-prospect-totals-2')->get('backlink-prospect-totals-2', 'BacklinkProspectController@getTotalsStatus2');

    //Buy
    Route::name('get-buy')->get('buy','BuyController@getList');
    Route::name('get-check-credit-auth')->get('check-credit-auth','BuyController@checkCreditAuth');
    Route::name('update-buy')->put('buy','BuyController@update');
    Route::name('update-buy-dislike')->post('buy-dislike','BuyController@updateDislike');
    Route::name('update-buy-like')->post('buy-like','BuyController@updateLike');
    Route::name('update-buy-uninterested')->post('buy-uninterested-new','BuyController@updateUninterestedNew');
    Route::name('update-buy-interested')->post('buy-interested-new','BuyController@updateInterestedNew');
    Route::name('update-uninterested')->post('buy-uninterested', 'BuyController@UpdateUninterested');
    Route::name('update-uninterested-multiple')->post('buy-uninterested-multiple', 'BuyController@UpdateMultipleUninterested');

    Route::name('update-buy-order-validate')->post('buy-order-validate','BuyController@validateOrder');

    Route::name('save-interested-details')->post('save-interested-details','BacklinksInterestedController@store');

    //Link Injection
    Route::name('link-injection-request')->post('link-injection-request','LinkInjectionController@request');
    Route::name('get-link-injections')->get('get-link-injections','LinkInjectionController@index');
    Route::name('get-link-injection-status-summary')->get('get-link-injection-status-summary','LinkInjectionController@statusSummary');
    Route::name('update-link-injections')->post('update-link-injections','LinkInjectionController@update');
    Route::name('approve-link-injections')->post('approve-link-injections','LinkInjectionController@approve');
    Route::name('purchase-link-injections')->post('purchase-link-injections','LinkInjectionController@purchase');
    Route::name('check-publisher-sellers')->post('check-publisher-sellers','LinkInjectionController@CheckPublisherSellers');
    Route::name('update-injection-seller')->post('update-injection-seller','LinkInjectionController@UpdateInjectionSeller');
    Route::name('decline-link-injection')->post('decline-link-injection','LinkInjectionController@DeclineLinkInjection');

    //Generate List
    Route::name('generate-list-upload-csv')->post('generate-list-upload-csv','GenerateListController@importCsv');
    Route::name('generate-list')->get('generate-list','GenerateListController@getList');
    Route::name('generate-list-delete')->post('generate-list-delete','GenerateListController@deleteGenerateList');
    Route::name('generate-list-ahref')->post('generate-list-ahref','GenerateListController@getAhrefs');
    Route::name('generate-list-compute-price')->post('generate-list-compute-price','GenerateListController@computePrice');
    Route::name('generate-list-add-url')->post('generate-list-add-url','GenerateListController@store');

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
    Route::name('update-multiple-in-charge')->post('/update-multiple-in-charge', 'AccountController@updateMultipleInCharge');
    Route::name('transfer-payment-info')->get('/transfer-payment-info', 'AccountController@transferPaymentInfo');
    Route::name('get-affiliate-list')->get('get-affiliate-list', 'AccountController@getAffiliateList');
    Route::name('get-affiliate-code-set')->get('/get-affiliate-code-set','AccountController@getAffiliateCodeSet');

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
    Route::name('view-csv-uploads')->get('/publisher/view-csv-uploads', 'PublisherController@viewCsvUploads');
    Route::name('csv-upload-log')->get('/publisher/csv-upload/log', 'PublisherController@viewCsvUploadLog');
    Route::name('publisher-get-summary')->get('/publisher/summary', 'PublisherController@getSummary');
    Route::name('publisher-valid')->post('/publisher/valid', 'PublisherController@validData');
    Route::name('best-price-log')->get('publisher/best-prices/log', 'PublisherController@bestPricesGenerationLog');
    Route::name('seller-incharge')->get('/seller-incharge/{user_id}', 'PublisherController@getListSellerIncharge');
    Route::name('publisher-qc-validation')->post('/publisher/qc-validation', 'PublisherController@qcValidationUpdate');
    Route::name('publisher-domain-zones')->get('/publisher/domain-zones', 'PublisherController@getDomainZoneExtensions');
    Route::name('generate-country')->post('/publisher/generate-country', 'PublisherController@generateCountry');

    //External Domain List Page
    Route::name('ext-get-alexa')->post('/ext/alexa', 'ExtDomainController@getAlexaLink');
    Route::name('ext-get-ahrefs')->put('/ext/ahrefs', 'ExtDomainController@getAhrefs');
    Route::name('ext-get')->get('/ext', 'ExtDomainController@getList');
    Route::name('ext-totals')->get('/ext-totals', 'ExtDomainController@getListTotals');
    Route::name('ext-create')->post('/ext', 'ExtDomainController@store');
    Route::name('ext-update-status')->put('/ext/status', 'ExtDomainController@updateStatus');
    Route::name('ext-update')->put('/ext', 'ExtDomainController@update');
    Route::name('ext-get-contacts')->post('/ext/get-contacts', 'ExtDomainController@crawlContact');
    Route::name('ext-upload-csv')->post('/ext/upload-csv', 'ExtDomainController@importExcel');
    Route::name('ext-delete')->delete('/ext', 'ExtDomainController@delete');
    Route::name('ext-update-multiple-status')->put('/ext/update-multiple-status', 'ExtDomainController@updateMultipleStatus');
    Route::name('ext-get-ext-seller')->get('ext/ext-seller','ExtDomainController@getListExtSeller');
    Route::name('ext-update-multiple-employee')->post('/update-multiple-employee','ExtDomainController@updateMultipleEmployee');
    Route::name('ext-export-filtered')->post('/ext/export-filtered', 'ExtDomainController@exportFiltered');
    Route::name('ext-check-urls')->post('/ext/check-urls', 'ExtDomainController@checkUrlsExisting');

    // Writers Validation
    Route::name('get-ext-writers')->get('ext-writers', 'WriterValidationController@getList');
    Route::name('add-exam')->post('add-exam', 'WriterValidationController@store');
    Route::name('update-exam')->post('update-exam', 'WriterValidationController@update');
    Route::name('get-exam-details')->get('get-exam-details', 'WriterValidationController@getExamDetails');
    Route::name('check-exam')->post('check-exam', 'WriterValidationController@checkExam');
    Route::name('update-exam-fail-reason')->post('update-exam-fail-reason', 'WriterValidationController@updateExamFailReason');

    //Internal Page
    Route::name('int-get')->get('/int', 'IntDomainController@getList');
    Route::name('int-create')->post('/int', 'IntDomainController@store');
    Route::name('int-update')->put('/int', 'IntDomainController@update');

    // Incomes
    Route::name('get-incomes')->get('incomes', 'IncomesController@getList');
    Route::name('update-incomes')->put('incomes', 'IncomesController@update');

    // Incomes Admin
    Route::name('get-incomes-admin')->get('incomes-admin', 'IncomesAdminController@getList');
    Route::name('get-incomes-admin-buyers')->get('/incomes-admin-buyers', 'IncomesAdminController@getOverallIncomesBuyers');

    // Followup Sales
    Route::name('get-sales')->get('sales', 'FollowupSalesController@getList');
    Route::name('get-status-summary-followup-sales')->get('get-status-summary-followup-sales', 'FollowupSalesController@statusSummary');
    Route::name('update-sales')->post('sales', 'FollowupSalesController@update');
    Route::name('process-pending-order')->post('process-pending-order', 'FollowupSalesController@processPendingOrder');

    //Backlink
    Route::resource('backlinks', 'BackLinkController');
    Route::name('buyers-bought')->get('buyers-bought', 'BackLinkController@getBuyerBought');
    Route::name('delete-backlinks')->post('delete-backlinks', 'BackLinkController@deleteBacklinks');
    Route::name('delete-backlinks-multiple')->post('delete-backlinks-multiple', 'BackLinkController@deleteMultipleBacklinks');
    Route::name('backlinks-summary-status')->get('backlinks-summary-status', 'BackLinkController@statusSummary');

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
    Route::name('get-roles')->get('/roles-list', 'RoleController@getListRoles');
    Route::name('update-roles')->post('/roles-update', 'RoleController@update');
    Route::name('delete-roles')->post('/roles-delete', 'RoleController@delete');
    Route::name('add-roles')->post('/roles-add', 'RoleController@store');
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
        Route::name('add-country')->post('/countries', 'CountryController@store');
        Route::name('add-language')->post('/language', 'ConfigController@storeLanguage');
        Route::name('get-language')->get('/language', 'ConfigController@getLanguage');
        Route::name('update-language')->put('/language', 'ConfigController@updateLanguage');
        Route::name('update-language-price-rates')->post('/language-price-rates', 'ConfigController@updateLanguagePriceRates');
        Route::name('update-country')->put('/countries', 'CountryController@edit');
        Route::name('get-configs')->get('/configs', 'ConfigController@getList');
        Route::name('update-configs')->put('/configs', 'ConfigController@edit');
        Route::name('get-payment')->get('/payments', 'PaymentController@getList');
        Route::name('add-payment')->post('/payments', 'PaymentController@store');
        Route::name('update-payment')->put('/payments', 'PaymentController@edit');
        Route::name('delete-payment-image')->delete('/payment-type/image/{id}', 'PaymentController@deletePaymentTypeImage');
        Route::name('add-payment-image')->post('/payment-type/image/{id}', 'PaymentController@uploadPaymentTypeImage');
        Route::name('get-email-access')->get('/email-access', 'ConfigController@getEmailAccessList');
        Route::name('post-email-access')->post('/email-access', 'ConfigController@addEmailAccess');
    });

    // System Logs
    Route::name('get-logs')->get('/logs', 'LogController@getList');
    Route::name('get-all-users')->get('/all-users', 'LogController@getAllUsers');
    Route::name('get-log-tables')->get('/logs/tables', 'LogController@getTables');
    Route::name('get-log-actions')->get('/logs/actions', 'LogController@getActions');
    Route::name('get-log-totals')->get('/logs/totals', 'LogController@getTotals');
    Route::name('system-logs-months')->get('/logs/months', 'LogController@getMonths');
    Route::name('system-logs-delete-month')->delete('/logs/flush/{month}', 'LogController@flushMonth');

    //Formula
    Route::name('get-formula')->get('/formula', 'ConfigController@getFormula');
    Route::name('update-formula')->post('/formula', 'ConfigController@updateFormula');

    // Ahref Subscription
    Route::name('subscription-info')->get('subscription-info', 'ConfigController@getSubscriptionInfo');

    // Alexa Consume
    Route::name('alexa-consume')->get('alexa-consume', 'ConfigController@getAlexaConsume');

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
        Route::name('mail-logs-totals')->get('/mail-logs-totals','MailgunController@mail_logs_totals');
        Route::name('get-user-email-list')->get('/user-email-list','AccountController@userEmailFilter');
        Route::name('get-mail-list')->get('/get-mail-list','MailgunController@get_mail_list');
        Route::name('get-user-list')->get('/get-user-list','MailSignatureController@getUsers');
        Route::name('get-signature-list')->get('/get-signature-list','MailSignatureController@getSignatures');
        Route::name('add-signature')->post('/add-signature','MailSignatureController@storeSignature');
        Route::name('update-signature')->put('/update-signature','MailSignatureController@updateSignature');
        Route::name('delete-signature')->delete('/delete-signature/{id}','MailSignatureController@destroy');
        Route::name('post-signature-image')->post('/post-signature-image','MailSignatureController@storeSignatureImage');
        Route::name('delete-signature-image')->post('/delete-signature-image','MailSignatureController@deleteSignatureImage');
        Route::name('retrieve-deleted-message')->post('/retrieve-deleted-email', 'MailgunController@retrieveMessage');

        // mailgun attachments
        Route::name('email_attachments')->get('/delete-old-attachments','MailgunController@deleteOldAttachments');

        // Validation Email
        Route::name('send-validation-email')->post('/send-validation-email', 'MailgunController@sendValidationEmail');

        // Deposit Email
        Route::name('send-deposit-email')->post('/send-deposit-email', 'MailgunController@sendDepositEmail');

        // drafts
        Route::name('save-draft')->post('/save-draft','MailDraftController@store');
        Route::name('get-drafts')->post('/get-drafts','MailDraftController@index');
        Route::name('delete-draft')->post('/delete-draft','MailDraftController@destroy');
        Route::name('delete-selected-drafts')->post('/delete-selected-drafts','MailDraftController@destroySelected');

        // auto reply
        Route::name('get-auto-reply')->post('/get-auto-reply','MailAutoReplyController@index');
        Route::name('add-auto-reply')->post('/add-auto-reply','MailAutoReplyController@store');
        Route::name('update-auto-reply')->put('/update-auto-reply','MailAutoReplyController@update');
        Route::name('delete-auto-reply')->post('/delete-auto-reply','MailAutoReplyController@destroy');
        Route::name('toggle-auto-reply')->post('/toggle-auto-reply','MailAutoReplyController@toggleAutoReply');
        Route::name('get-auto-reply-state')->get('/get-auto-reply-state','MailAutoReplyController@getAutoReplyState');
    });

    Route::name('labels')->resource('/label','LabelController');
    Route::name('labels')->get('/label_list/{email}','LabelController@getLabels');

     //Notifications
    Route::name('notifications.get')->get('/notifications/{user_id}', 'NotificationController@getUserNotifications');
    Route::name('notifications.seen')->put('/notifications/{user_id}', 'NotificationController@setUserNotificationsSeen');
    Route::name('notifications.get-all')->get('/notifications-all/{email}', 'NotificationController@getAllUserNotifications');

    //Continents
    Route::name('get-list-continents')->get('/continent-list', 'ContinentController@getListContinent');

    //Compute for best price
    Route::name('generate-best-price')->post('generate-best-price', 'PublisherController@generateBestPrice');

    //Paypal Integration
    Route::name('create-order')->post('/paypal/order/create', 'PayPalController@createOrder');
    Route::name('capture-order')->post('/paypal/order/{id}/capture', 'PayPalController@captureOrder');

    //Graphs
    Route::name('orders-graph')->get('/graphs/orders', 'GraphsController@getOrdersGraph');
    Route::name('seller-valid-graph')->get('/graphs/seller-valid', 'GraphsController@getSellerValidGraph');
    Route::name('buyer-valid-graph')->get('/graphs/buyer-valid', 'GraphsController@getBuyerValidGraph');
    Route::name('writer-valid-graph')->get('/graphs/writer-valid', 'GraphsController@getWriterValidGraph');
    Route::name('url-valid-graph')->get('/graphs/url-valid', 'GraphsController@getUrlValidGraph');
    Route::name('url-valid-graph')->get('/graphs/url-valid-price', 'GraphsController@getUrlValidPriceGraph');
    Route::name('url-seller-statistics')->get('/graphs/url-seller-statistics', 'GraphsController@getUrlSellerStatisticsGraph');
    Route::name('prospect-qualified-registered')->get('/graphs/prospect-qualified-registered', 'GraphsController@getProspectQualifiedVsRegisteredGraph');

    //Download files
    Route::name('download-paypal-invoice')->get('/files/invoice/paypal/{id}', 'WalletTransactionController@downloadPaypalInvoice');
    Route::name('download-paypal-proof-seller')->get('/files/proof/paypal/seller/{id}', 'SellerBillingController@downloadPaypalProof');
    Route::name('download-paypal-proof-writer')->get('/files/proof/paypal/writer/{id}', 'WriterBillingController@downloadPaypalProof');

    Route::name('crypto-address-update')->post('/crypto/usdt', 'ConfigController@updateCryptoAddress');
    Route::name('crypto-address-update-btc')->post('/crypto/btc', 'ConfigController@updateCryptoAddressBtc');
    Route::name('crypto-address-update-eth')->post('/crypto/eth', 'ConfigController@updateCryptoAddressEth');

    // tools
    Route::name('tools-get')->get('/tools', 'ToolController@index');
    Route::name('tools-store')->post('/tools', 'ToolController@store');
    Route::name('tools-update')->put('/tools','ToolController@update');
    Route::name('tools-delete')->delete('/tools/{id}','ToolController@destroy');

    // modules
    Route::name('module-get')->get('/module', 'ModuleController@index');
    Route::name('module-store')->post('/module', 'ModuleController@store');
    Route::name('module-update')->put('/module','ModuleController@update');
    Route::name('module-delete')->delete('/module/{id}','ModuleController@destroy');
    Route::name('module-filter-values')->get('/module/get-filter-values','ModuleController@getFilterValues');

    // purchase category
    Route::name('purchases-category-get')->get('/purchases/category', 'PurchaseCategoryController@index');
    Route::name('purchases-category-selection')->get('/purchases/category/selection', 'PurchaseCategoryController@selection');
    Route::name('purchases-category-store')->post('/purchases/category', 'PurchaseCategoryController@store');
    Route::name('purchases-category-update')->put('/purchases/category','PurchaseCategoryController@update');
    Route::name('purchases-category-delete')->delete('/purchases/category/{id}','PurchaseCategoryController@destroy');

    // purchase type
    Route::name('purchases-type-selection')->get('/purchases/type/selection', 'PurchaseTypeController@selection');
    Route::name('purchases-type-store')->post('/purchases/type', 'PurchaseTypeController@store');
    Route::name('purchases-type-update')->put('/purchases/type','PurchaseTypeController@update');

    // purchase
    Route::name('purchases-get')->get('/purchases', 'PurchasesController@index');
    Route::name('purchases-buyer-selection')->get('/purchases/buyer-selection/{mode}', 'PurchasesController@selection');
    Route::name('purchases-store')->post('/purchases', 'PurchasesController@store');
    Route::name('purchases-update')->put('/purchases','PurchasesController@update');
    Route::name('purchases-delete')->delete('/purchases/{purchase}', 'PurchasesController@delete');

    // purchase modules
    Route::name('purchases-type-selection')->get('/purchases/modules/selection', 'PurchasesController@getPurchaseModules');

    // purchase graphs
    Route::name('purchases-get-category-report')->get('/purchases/graphs/category', 'PurchasesController@getCategoryReportData');
    Route::name('purchases-get-purchase-type-report')->get('/purchases/graphs/purchase-type', 'PurchasesController@getPurchaseTypeReportData');
    Route::name('purchases-get-payment-method-report')->get('/purchases/graphs/payment-method', 'PurchasesController@getPaymentMethodReportData');

    // renew
    Route::name('renew-post')->post('/renew', 'RenewController@renew');

    // generate article for follow up sales
    Route::name('generate-article')->post('/generate-article', 'FollowupSalesController@generateArticle');
    Route::name('update-publisher-inc-article')->post('/update-publisher-inc-article', 'FollowupSalesController@updatePublisherIncArticle');

    // users payment type
    Route::name('users-payment-type-history')->get('/users-payment-type/history/{id}','UsersPaymentTypeController@getHistory');

    // Affiliate Code
    Route::name('generate-affiliate-code')->post('/generate-affiliate-code', 'AffiliateCodeController@store');
    Route::name('get-affiliate-codes')->get('/get-affiliate-codes', 'AffiliateCodeController@index');
    Route::name('get-all-affiliates')->get('/get-all-affiliates', 'AffiliateCodeController@getAllAffiliates');
    Route::name('get-all-affiliate-campaigns')->get('/get-all-affiliate-campaigns', 'AffiliateCodeController@getAllAffiliateCampaigns');
    Route::name('get-affiliate-user-campaigns')->get('/get-affiliate-user-campaigns/{id}', 'AffiliateCodeController@getAffiliateUserCampaigns');
    Route::name('get-affiliate-user-buyers')->get('/get-affiliate-user-buyers/{id}', 'AffiliateCodeController@getAffiliateUserBuyers');
    Route::name('get-affiliate-campaign-buyers')->get('/get-affiliate-campaign-buyers/{id}', 'AffiliateCodeController@getAffiliateCampaignBuyers');
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
//removing http,www,https
Route::name('test')->get('/test-remove-http', 'PurchaseController@testRemoveHttp');


// updating price basis
Route::name('update-price-basis-publisher')->get('/update-price-basis-publisher', 'ConfigController@updatePriceBasis');

// backlinks prospect
Route::name('fetch-backlink-prospect')->get('/fetch-backlink-prospect', 'ExternalBacklinkProspectController@fetchBacklinkProspect');

