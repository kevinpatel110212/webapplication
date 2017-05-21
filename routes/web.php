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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::any('home', 'HomeController@index')->name('home');

Route::any('anyData', function (Illuminate\Http\Request $request) {
        return App\User::dataOperation($request);
})->name('anyData');

Route::any('contact', function (Illuminate\Http\Request $request) {
        return view('contact');
})->name('contact');

Route::any('contactlistaddedit', function (Illuminate\Http\Request $request) {
        return App\Contact::dataOperation($request);
})->name('contactlistaddedit');