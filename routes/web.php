<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Frontend\HomeController')->name('landing');
Route::get('/contact', 'Frontend\ContactController')->name('contact');
Route::get('/pricing', 'Frontend\PricingController')->name('pricing');
Route::get('/terms-and-conditions', 'Frontend\TermsAndConditionsController')->name('terms');
Route::get('/privacy-policy', 'Frontend\PrivacyPolicyController')->name('policy');
Auth::routes();

Route::get('verify/resend', 'Auth\TwoFactorController@resend')->name('verify.resend');
Route::resource('verify', 'Auth\TwoFactorController')->only(['index', 'store']);


// By NP
Route::get('site-analysis-details/{site}', 'SiteController@analysisDetails')->name('site.analysis.details');

Route::middleware(['auth', 'twofactor'])->group(function () {

        Route::post('/themes/filter', 'ThemeController@ajax');
        Route::post('/plugins/filter', 'PluginController@ajax');
        Route::get('/domains', 'DomainController@index')->name('domains.index');
        Route::get('/domains/{domain}/maintenance', 'DomainController@edit')->name('domains.edit');
        Route::post('/domains/{domain}/update', 'DomainController@update')->name('domains.update');
        Route::get('/domains/{domain}/dns-and-nameserver', 'DomainController@dns')->name('domains.dns');
        Route::post('/sites/domain', 'DomainController@check_domain');
        Route::post('/sites/domain/price', 'DomainController@get_domain_price');

        Route::get('support-ticket/create');
        Route::resource('sites', 'SiteController');
        Route::get('form-reset', 'SiteController@resetForm')->name('sites.resetForm');
        Route::post('sites/restart', 'SiteController@restartSite')->name('sites.restart');
        Route::get('thank-you', 'ThankYouController')->name('thankYou');
        Route::get('profile', 'UserController@edit');
        Route::post('profile', 'UserController@update')->name('update.profile');
        Route::get('/complete-subscription', 'PaypalController@completeSubscription');
        Route::post('/sites/coupon', 'CouponController@checkCoupon');

        Route::get('/home', 'HomeController@dashboard')->name('home');
        Route::get('/faq', 'HomeController@faq')->name('faq');
        Route::get('/notification-mark-as-read', 'HomeController@notificationMarkAsRead')->name('notification-read');
        //For clear notification
        Route::get('/clear-notifications', 'HomeController@ClearNotification');
        Route::get('/generate-score', 'HomeController@generateScore');
        Route::get('/score-details', 'HomeController@scoreDetails');
        Route::get('/check-score-process', 'HomeController@checkScoreProcess');

        Route::get('/directory/themes/list', 'DirectoryController@themes')->name('themes.list');
        Route::get('/directory/plugins/list', 'DirectoryController@plugins')->name('plugins.list');
        Route::post('/directory/download', 'DirectoryController@download');

        Route::resource('users', 'UserController');

        Route::resource('support-ticket', 'SupportTicketController');
        Route::get('my_tickets', 'SupportTicketController@userTickets');
        Route::post('/user_tickets', 'SupportTicketController@userTickets');

        Route::resource('stripe-card', 'StripeController');
        Route::resource('billing', 'BillingController');

        Route::get('renew-payment', 'PaymentController@renewPayment')->name('renew');
        Route::post('renew-payment', 'PaymentController@submitPayment')->name('renew');

        Route::post('comment', 'CommentsController@postComment');

        Route::get('/merchant-review-page', 'AmazonPayController@merchantReviewPage');
        Route::get('/merchant-confirm-payment', 'AmazonPayController@paymentUpdate')->name('merchant.confirm.payment');
        Route::get('/merchant-review-confirm', 'AmazonPayController@merchantConfirmPage');
});
Route::get('/only-for-test', 'TestController');

//Instagram Routes
Route::get('auth/redirect/instagram', 'SocialLoginController@redirectToInstagramProvider')->name('instagram');
Route::get('/instagram_login', 'SocialLoginController@instagramProviderCallback');

//----------with social login--------------
Route::get('/auth/redirect/{provider}', 'SocialLoginController@redirect')->name('provider');
Route::get('/{provider}', 'SocialLoginController@callback');
