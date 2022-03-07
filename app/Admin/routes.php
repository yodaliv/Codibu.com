<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('themes', ThemesController::class);
    $router->resource('plugins', PluginsController::class);
    $router->resource('sites', SitesController::class);
    $router->resource('site-types', SiteTypeController::class);
    $router->resource('plans', PlanController::class);
    $router->resource('plan-specs', PlanSpecController::class);
    $router->resource('networks', NetworkController::class);
    $router->resource('demos', DemoController::class);
    $router->resource('faqs', FaqController::class);
    $router->resource('services', ServiceController::class);
    $router->resource('sections', SectionController::class);
    $router->resource('site-users', SiteUsersController::class);
    $router->resource('page-editors', PageEditorController::class);
    //Route by VPN start
    $router->resource('coupons', CouponController::class);
    //Route by VPN end
    //Support Tickets Routes
    $router->resource('support-tickets', SupportTicketsController::class);
    $router->resource('auth/users', UserController::class)->names('admin.auth.users');
    $router->post('admin-comment', 'CommentsController@postComment');
    $router->resource('testimonials', TestimonialController::class);
});
