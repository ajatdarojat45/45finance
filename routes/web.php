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
   if (!Auth::guest()) {
      return view('dashboard');
   }
    return view('login');
});

Route::get('/register', function () {
   if (!Auth::guest()) {
      return view('dashboard');
   }
    return view('register');
});


Auth::routes();

Route::get('/home', function () {
   return redirect('dashboard');
});

Route::group(['middleware' =>'auth'], function(){
   Route::get('/dashboard', function () {
      return view('dashboard');
   })->name('dashboard');

   // Transaction
   Route::get('transaction/index', 'TransactionController@index')->name('transaction/index');
   Route::post('transaction/importExcel', 'TransactionController@importExcel')->name('transaction/importExcel');
   Route::get('transaction/exportToExcel/{date1}/{date2}/{type}', 'TransactionController@exportToExcel')->name('transaction/exportToExcel');
   Route::get('transaction/exportToPdf/{date1}/{date2}', 'TransactionController@exportToPdf')->name('transaction/exportToPdf');
});
