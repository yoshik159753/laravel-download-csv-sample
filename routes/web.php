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

Route::get('/', 'Welcome@welcome')->name('welcome');
Route::get('/csv/case1', 'Welcome@downloadCsvCase1')->name('downloadCsvCase1');
Route::get('/csv/case2', 'Welcome@downloadCsvCase2')->name('downloadCsvCase2');
Route::get('/csv/case3', 'Welcome@downloadCsvCase3')->name('downloadCsvCase3');
Route::get('/csv/case4', 'Welcome@downloadCsvCase4')->name('downloadCsvCase4');
Route::get('/csv/case5', 'Welcome@downloadCsvCase5')->name('downloadCsvCase5');
Route::get('/csv/case6', 'Welcome@downloadCsvCase6')->name('downloadCsvCase6');
