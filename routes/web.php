<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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



//admin
Route::group(['prefix' => 'admin'], function() {
    Route::get('login')->name('login')->uses('Auth\LoginController@showLoginForm')->middleware('guest');
    Route::post('login')->name('login.attempt')->uses('Auth\LoginController@login')->middleware('guest');

    Route::group(['middleware' => ['auth']], function () {
        Route::post('logout')->name('logout')->uses('Auth\LoginController@logout');
        // admin Dashboard
        Route::get('/')->name('dashboard')->uses('DashboardController');
        //user
        Route::get('users')->name('users')->uses('UsersController@index')->middleware('remember');
        Route::get('users/create')->name('users.create')->uses('UsersController@create');
        Route::post('users')->name('users.store')->uses('UsersController@store');
        Route::get('users/edit/{user}')->name('users.edit')->uses('UsersController@edit');
        Route::put('users/{user}')->name('users.update')->uses('UsersController@update');
        Route::delete('users/{user}')->name('users.destroy')->uses('UsersController@destroy');
        Route::put('users/restore/{user}')->name('users.restore')->uses('UsersController@restore');
        //FileManager
        Route::post('upload')->name('filemanager.upload')->uses('FilemanagerController@upload');
        Route::post('upload/zip')->name('filemanager.upload')->uses('FilemanagerController@uploadZip');
        Route::get('fm/delete')->name('filemanager.delete')->uses('FilemanagerController@deleteZip');
        Route::get('fm/optimize')->name('filemanager.optimize')->uses('FilemanagerController@optimizationFiles');
        Route::get('fm/unzip')->name('filemanager.unzip')->uses('FilemanagerController@unZip');
        Route::get('fm/index')->name('filemanager.unzip')->uses('FilemanagerController@index');
        //Containers
        Route::get('containers')->name('containers')->uses('ContainerController@index')->middleware('remember');
        Route::get('containers/create')->name('containers.create')->uses('ContainerController@create');
        Route::post('containers')->name('containers.store')->uses('ContainerController@store');
        Route::get('containers/edit/{container}')->name('containers.edit')->uses('ContainerController@edit');
        Route::put('containers/{container}')->name('containers.update')->uses('ContainerController@update');
        Route::delete('containers/{container}')->name('containers.destroy')->uses('ContainerController@destroy');
        //Cars
        Route::get('cars')->name('cars')->uses('CarController@index')->middleware('remember');
        Route::get('cars/create')->name('cars.create')->uses('CarController@create');
        Route::post('cars')->name('cars.store')->uses('CarController@store');
        Route::get('cars/edit/{car}')->name('cars.edit')->uses('CarController@edit');
        Route::put('cars/{car}')->name('cars.update')->uses('CarController@update');
        Route::delete('cars/{car}')->name('cars.destroy')->uses('CarController@destroy');        
    });



});
Route::get('/')->uses('Front\IndexController@index');
Route::post('/search')->uses('Front\IndexController@search');
Route::get('/search/container/{container}')->uses('Front\IndexController@searchByContainer')->name('search.container');
Route::get('/search/car/{car}')->uses('Front\IndexController@searchByCar')->name('search.car');
Route::get('/download/images/cars/{image}', function($image){
    return response()->download('./images/cars/'.$image);
});

Route::get('/download/images/containers/{image}', function($image){
    return response()->download('./images/containers/'.$image);
});

Route::get('/download/{car}/{field}', 'Front\DownloadController');

Route::get('test', function(){
    $directory = 'containers/';

    $directory = Str::start($directory, 'images/');
    
    dd($directory);
});


