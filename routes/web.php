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

Route::get("/", "HomeController@index")->name("home");
Route::post("/store", "HomeController@store")->name("storeAsinCode");

Route::get("/view/{id}/{tag?}", "HomeController@view")->name("viewAsinCode");
Route::get("/view-comment/{id}/{tag?}", "HomeController@data")->name("viewAsinCodeData");
//Route::get("/scrape-data", "HomeController@scrapeData")->name("scrapeData");
Route::get("/tags","HomeController@getTags")->name('getTags');
Route::post("/add-tag/{id}","HomeController@addTag")->name('addTag');
Route::delete("/remove-tag/{id}","HomeController@removeTag")->name('removeTag');
Route::get("/analysis/{id}","HomeController@viewAnalysis")->name('viewAnalysis');
