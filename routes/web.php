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
Route::get('/csv/case7', 'Welcome@downloadCsvCase7')->name('downloadCsvCase7');
Route::get('/csv/case8', 'Welcome@downloadCsvCase8')->name('downloadCsvCase8');
Route::get('/csv/case9', 'Welcome@downloadCsvCase9')->name('downloadCsvCase9');
Route::get('/csv/case10', 'Welcome@downloadCsvCase10')->name('downloadCsvCase10');
Route::get('/csv/caseZ1', 'Welcome@downloadCsvCaseZ1')->name('downloadCsvCaseZ1');
Route::get('/csv/caseZ2', 'Welcome@downloadCsvCaseZ2')->name('downloadCsvCaseZ2');
