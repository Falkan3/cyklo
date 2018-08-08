<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => '{lang?}'], function () {
    Route::get('/', 'MainController@index')->name('index');
    Route::get('store', 'MainController@store');
    Route::get('catalog', 'MainController@catalog');
    Route::get('form', 'MainController@form');


    Route::get('home', 'HomeController@index');
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout');

    // Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm');
    Route::post('register', 'Auth\RegisterController@register');
    Route::get('verify_email/{token}', 'Auth\RegisterController@verify');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

    //Admin routes
    Route::group(['prefix' => 'admin'], function() {
        Route::get('/', 'AdminController@index');
    });

    //Helper routes
    //Routes containing utility tools and API calls - for example retrieving an image by id or list of products.
    Route::group(['prefix' => 'helper'], function() {
        //lang
        Route::get('switch_lang/{new_lang}', 'HelperController@switchLanguage');

        //images
        Route::get('image/{id}', 'HelperController@getImage');
        Route::get('images/{ids}', 'HelperController@getImages');

        //products
        Route::get('productitems/listproducts', 'HelperController@listProducts');
    });

    //REST
    Route::group(['prefix' => 'REST', 'middleware' => 'admin'], function() {
        //images
        Route::get('images/index', 'REST\Images\ImageController@index');
        Route::resource('images', 'REST\Images\ImageController');
        Route::resource('imagecategories', 'REST\Images\ImageCategoryController');
        Route::get('imageselector', 'REST\Images\ImageController@getImagesForImageSelector');
        //products
        Route::get('productitems/index', 'REST\Products\ProductItemController@index');
        Route::resource('productitems', 'REST\Products\ProductItemController');
        Route::resource('productcategories', 'REST\Products\ProductCategoryController');
    });
});
