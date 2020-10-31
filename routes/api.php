<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::resource('movies', 'MoviesController@index');
Route::resource('/movies', 'MoviesController', array('except' => array('filter', 'destroy')));
Route::get('/movies/filter/{data}', 'MoviesController@filter');
Route::post('/movies/removefromlist', 'MoviesController@destroy');
Route::resource('/contributors', 'ContributorsController', array('except' => array('filter')));
Route::get('/contributors/filter/{data}', 'ContributorsController@filter');
Route::resource('/companies', 'CompanyController', array('except' => array('filter')));
Route::get('/companies/filter/{data}', 'CompanyController@filter');
Route::resource('/lists', 'MovieListController', array('except' => array('filter', 'listInfo')));
Route::get('/lists/filter/{data}', 'MovieListController@filter');
Route::get('/lists/info/{id}', 'MovieListController@listInfo');
Route::resource('/users', 'UsersController', array('except' => array('updateFollowingStatus', 'fetchFollowLists')));
Route::post('/users/updatefollowstatus', 'UsersController@updateFollowingStatus');
Route::post('/users/fetchFollowLists', 'UsersController@fetchFollowLists');
Route::resource('/reviews', 'ReviewsController', array('except' => array('filter', 'showReviews')));
Route::get('/reviews/filter/{data}', 'ReviewsController@filter');
Route::get('/reviews/reviewsforuser/{id}', 'ReviewsController@showReviews');
Route::post('/avatars/store', 'ProfileImageController@store');
Route::get('/avatars/get', 'ProfileImageController@getAvatar');
Route::get('/avatars/delete', 'ProfileImageController@deleteAvatar');
Route::resource('/locations', 'LocationsController');
Route::post('/search', 'SearchController@search')->name('create');
Route::get('password/reset/{token?}', 'Auth\resetPasswordController@showResetForm');
// Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');










