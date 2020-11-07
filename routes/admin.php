<?php

use Illuminate\Support\Facades\Route;

define('PAGINATE', 10);

//Route::get('/', 'Admin\DashboardController@');

Route::group(['namespace' => 'Admin','middleware' => 'auth:admin'], function(){
    Route::get('/', 'DashboardController@index')->name('admin.dashboard');
    ##################### Begin Languages Routes ##############################
    Route::group(['prefix' => 'languages'], function(){
        Route::get('/', 'LanguagesController@index')->name('admin.languages');
        Route::get('/addLang', 'LanguagesController@create')->name('admin.languages.create');
        Route::post('/addLang', 'LanguagesController@store')->name('admin.languages.store');
        Route::get('/{id}/edit', 'LanguagesController@edit')->name('admin.languages.edit');
        Route::put('/{id}', 'LanguagesController@update')->name('admin.languages.update');
        Route::get('/delete/{id}', 'LanguagesController@destroy')->name('admin.languages.delete');
    });
    ##################### Ende Languages Routes ##############################
    ##################### Begin main_categories Routes ##############################
    Route::group(['prefix' => 'main_categories'], function(){
        Route::get('/', 'MainCategoryController@index')->name('admin.main_categories');
        Route::get('/add_mainCategory', 'MainCategoryController@create')->name('admin.main_categories.create');
        Route::post('/add_mainCategory', 'MainCategoryController@store')->name('admin.main_categories.store');
        Route::get('/{id}/edit', 'MainCategoryController@edit')->name('admin.main_categories.edit');
        Route::put('/{id}', 'MainCategoryController@update')->name('admin.main_categories.update');
        Route::get('/delete/{id}', 'MainCategoryController@destroy')->name('admin.main_categories.delete');
        Route::get('/changeStaus/{id}', 'MainCategoryController@changeStatus')->name('admin.main_categories.changeStatus');
    //     Route::get('/tabs', function(){
    //         return view('admin.main_categories.tabsTest');
    //     });
    });
    ##################### Ende main_categories Routes ##############################
    ##################### Begin Sub_categories Routes ##############################
    Route::group(['prefix' => 'sub_categories'], function(){
        Route::get('/', 'SubCategoryController@index')->name('admin.sub_categories');
        Route::get('/add_subCategory', 'SubCategoryController@create')->name('admin.sub_categories.create');
        Route::post('/add_subCategory', 'SubCategoryController@store')->name('admin.sub_categories.store');
        Route::get('/{id}/edit', 'SubCategoryController@edit')->name('admin.sub_categories.edit');
        Route::put('/{id}', 'SubCategoryController@update')->name('admin.sub_categories.update');
        Route::get('/delete/{id}', 'SubCategoryController@destroy')->name('admin.sub_categories.delete');
        Route::get('/changeStaus/{id}', 'SubCategoryController@changeStatus')->name('admin.sub_categories.changeStatus');
    //     Route::get('/tabs', function(){
    //         return view('admin.main_categories.tabsTest');
    //     });
    });
    ##################### Ende Sub_categories Routes ##############################
    ##################### Begin Vendors Routes ##############################
    Route::group(['prefix' => 'vendors'], function(){
        Route::get('/', 'VendorsController@index')->name('admin.vendors');
        Route::get('/add_vendor', 'VendorsController@create')->name('admin.vendors.create');
        Route::post('/add_vendor', 'VendorsController@store')->name('admin.vendors.store');
        Route::get('/{id}/edit', 'VendorsController@edit')->name('admin.vendors.edit');
        Route::post('/{id}', 'VendorsController@update')->name('admin.vendors.update');
        Route::get('/delete/{id}', 'VendorsController@destroy')->name('admin.vendors.delete');
        Route::get('/changeStaus/{id}', 'VendorsController@changeStatus')->name('admin.vendors.changeStatus');
     });
    ##################### Ende Vendors Routes ##############################
});
// Prefix admin wurde in RouteServiceProvider festgesetzt und braucht hier nicht geschrieben werden
Route::group(['namespace' => 'Admin','middleware' => 'guest:admin'], function(){

    Route::get('login', 'LoginController@getLogin')->name('admin.login');
    Route::post('login', 'LoginController@login')->name('admin.post.login');
    Route::post('logout', 'LoginController@logout')->name('admin.logout');
});