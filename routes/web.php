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

Route::get('log/store', 'LogController@store')->name('log/store');

Auth::routes();

Route::get('/home', function () {
   return redirect('dashboard');
});

Route::get('/documentation', function () {
   return view('documentation');
})->name('documentation');

Route::group(['middleware' =>'auth'], function(){
   Route::get('/dashboard', function () {
      return view('dashboard');
   })->name('dashboard');

   // Transaction
   Route::get('transaction/index', 'TransactionController@index')->name('transaction/index');
   Route::get('transaction/destroy/{id}', 'TransactionController@destroy')->name('transaction/destroy');
   Route::post('transaction/importExcel', 'TransactionController@importExcel')->name('transaction/importExcel');
   Route::get('transaction/reportByYear', 'TransactionController@reportByYear')->name('transaction/reportByYear');
   Route::get('transaction/reportByDate', 'TransactionController@reportByDate')->name('transaction/reportByDate');
   Route::get('transaction/reportByMonth', 'TransactionController@reportByMonth')->name('transaction/reportByMonth');
   Route::post('transaction/multipleDestroy', 'TransactionController@multipleDestroy')->name('transaction/multipleDestroy');
   Route::get('transaction/exportToPdf/{date1}/{date2}/{wallet}/{category}', 'TransactionController@exportToPdf')->name('transaction/exportToPdf');
   Route::get('transaction/exportToExcel/{date1}/{date2}/{type}/{wallet}/{category}', 'TransactionController@exportToExcel')->name('transaction/exportToExcel');
   Route::get('transaction/reportByCategory', 'TransactionController@reportByCategory')->name('transaction/reportByCategory');

   // $logs
   Route::get('log/index', 'LogController@index')->name('log/index');
});
