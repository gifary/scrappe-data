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

Route::get("/view/{id}", "HomeController@view")->name("viewAsinCode");
Route::get("/view-comment/{id}", "HomeController@data")->name("viewAsinCodeData");
//Route::get("/scrape-data", "HomeController@scrapeData")->name("scrapeData");
