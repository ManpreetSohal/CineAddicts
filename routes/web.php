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
    return view('home');
});

Auth::routes();


//Route::get('/{any}', 'HomeController@index')->where('any', '^(?!api).*$');

 Route::get('/home', 'HomeController@index')->name('home');
 Route::view('/movies', 'movies/index')->name('movies');
 Route::View('/movies/{id}', 'movies/movie')->name('movie');
//  Route::get('/music', 'MusicController@index')->name('music');
 Route::View('/contributors', 'contributors/index')->name('contributors');
 Route::View('/contributors/{id}', 'contributors/contributor')->name('contributor');
 Route::View('/companies/{id}', 'companies/company')->name('company');
 Route::View('/companies', 'companies/index')->name('companies');
 Route::View('/lists', 'lists/index')->name('lists');
 Route::View('/lists/info/{id}', 'lists/list')->name('list');
 Route::View('/user', 'user/profile')->name('list');
 Route::View('/users/{id}', 'user/profile')->name('list');
//  Route::get('/posts', 'PostsController@index')->name('posts');
 Route::View('/search', 'search/search')->name('search');
 Route::View('/locations/{id}', 'locations/location')->name('location');
 Route::View('/error', 'errors/error')->name('registererror');
