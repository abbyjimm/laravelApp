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
Route::group(['middleware' => ['guest']], function () {
    Route::get('/','Auth\loginController@showLoginForm');
    Route::post('/login', 'Auth\loginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function () {
    
    Route::post('/logout', 'Auth\loginController@logout')->name('logout');
    
    Route::get('/main', function () {
        return view('content/content');
    })->name('main');
    
    Route::group(['middleware' => ['Grocer']], function () {
        Route::get('/category', 'CategoryController@index');
        Route::post('/category/insert', 'CategoryController@store');
        Route::put('/category/update', 'CategoryController@update');
        Route::put('/category/disable', 'CategoryController@disable');
        Route::put('/category/able', 'CategoryController@able');
        Route::get('/category/selectCategory', 'CategoryController@selectCategory');
        
        Route::get('/article', 'ArticleController@index');
        Route::get('/article/listArticles', 'ArticleController@listArticles');
        Route::get('/article/searchArticle', 'ArticleController@searchArticle');
        Route::post('/article/insert', 'ArticleController@store');
        Route::put('/article/update', 'ArticleController@update');
        Route::put('/article/disable', 'ArticleController@disable');
        Route::put('/article/able', 'ArticleController@able');

        Route::get('/supplier', 'SupplierController@index');
        Route::post('/supplier/insert', 'SupplierController@store');
        Route::put('/supplier/update', 'SupplierController@update');
        Route::get('/supplier/selectSupplier', 'SupplierController@selectSupplier');

        Route::get('/income', 'IncomeController@index');
        Route::post('/income/insert', 'IncomeController@store');
        Route::put('/income/disable', 'IncomeController@disable');
    });    
    
    Route::group(['middleware' => ['Seller']], function () {
        Route::get('/customer', 'CustomerController@index');
        Route::post('/customer/insert', 'CustomerController@store');
        Route::put('/customer/update', 'CustomerController@update'); 
    });
          
    Route::group(['middleware' => ['admin']], function () {
        Route::get('/category', 'CategoryController@index');
        Route::post('/category/insert', 'CategoryController@store');
        Route::put('/category/update', 'CategoryController@update');
        Route::put('/category/disable', 'CategoryController@disable');
        Route::put('/category/able', 'CategoryController@able');
        Route::get('/category/selectCategory', 'CategoryController@selectCategory');
        
        Route::get('/article', 'ArticleController@index');
        Route::get('/article/listArticles', 'ArticleController@listArticles');
        Route::get('/article/searchArticle', 'ArticleController@searchArticle');
        Route::post('/article/insert', 'ArticleController@store');
        Route::put('/article/update', 'ArticleController@update');
        Route::put('/article/disable', 'ArticleController@disable');
        Route::put('/article/able', 'ArticleController@able');

        Route::get('/supplier', 'SupplierController@index');
        Route::post('/supplier/insert', 'SupplierController@store');
        Route::put('/supplier/update', 'SupplierController@update');
        Route::get('/supplier/selectSupplier', 'SupplierController@selectSupplier');

        Route::get('/income', 'IncomeController@index');
        Route::post('/income/insert', 'IncomeController@store');
        Route::put('/income/disable', 'IncomeController@disable');

        Route::get('/customer', 'CustomerController@index');
        Route::post('/customer/insert', 'CustomerController@store');
        Route::put('/customer/update', 'CustomerController@update'); 

        Route::get('/role', 'RoleController@index');
        Route::get('/role/selectRole', 'RoleController@selectRole');
        
        Route::get('/user', 'UserController@index');
        Route::post('/user/insert', 'UserController@store');
        Route::put('/user/update', 'UserController@update');
        Route::put('/user/disable', 'UserController@disable');
        Route::put('/user/able', 'UserController@able'); 
         
    });   
});

//Route::get('/home', 'HomeController@index')->name('home');
