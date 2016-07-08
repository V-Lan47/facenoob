<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
	Home
*/
Route::get('/',[
	'uses' => '\Facenoob\Http\Controllers\HomeController@index',
	'as' => 'home',
]);

/*
	Authentication
*/
Route::get('/signup',[
	'uses' => '\Facenoob\Http\Controllers\AuthController@getSignup',
	'as' => 'auth.signup',
	'middleware' => ['guest'],
]);

Route::post('/signup',[
	'uses' => '\Facenoob\Http\Controllers\AuthController@postSignup',
	'middleware' => ['guest'],
]);

Route::get('/signin',[
	'uses' => '\Facenoob\Http\Controllers\AuthController@getSignin',
	'as' => 'auth.signin',
	'middleware' => ['guest'],
]);

Route::post('/signin',[
	'uses' => '\Facenoob\Http\Controllers\AuthController@postSignin',
	'middleware' => ['guest'],

]);

Route::get('/signout',[
	'uses' => '\Facenoob\Http\Controllers\AuthController@getSignout',
	'as' => 'auth.signout',
]);
/*
Search
*/
Route::get('/search',[
	'uses' => '\Facenoob\Http\Controllers\SearchController@getResults',
	'as' => 'search.results',

]);
/*
User Profile
*/
Route::get('/user/{username}',[
	'uses' => '\Facenoob\Http\Controllers\ProfileController@getProfile',
	'as' => 'profile.index',
]);

Route::get('/profile/edit',[
	'uses' => '\Facenoob\Http\Controllers\ProfileController@getEdit',
	'as' => 'profile.edit',
	'middleware' => ['auth'],
]);

Route::post('/profile/edit',[
	'uses' => '\Facenoob\Http\Controllers\ProfileController@postEdit',
	'middleware' => ['auth'],
]);
/*
Friends
*/
Route::get('/friends',[
	'uses' => '\Facenoob\Http\Controllers\FriendsController@getIndex',
	'as' => 'friends.index',
	'middleware' => ['auth'],
]);

Route::get('/friends/add/{username}',[
	'uses' => '\Facenoob\Http\Controllers\FriendsController@getAdd',
	'as' => 'friends.add',
	'middleware' => ['auth'],
]);

Route::get('/friends/accept/{username}',[
	'uses' => '\Facenoob\Http\Controllers\FriendsController@getAccept',
	'as' => 'friends.accept',
	'middleware' => ['auth'],
]);

Route::get('/alert', function(){
	return redirect()->route('home')->with('info', 'You have successfully signed up! You may now proceed to log in.');

});
/*
Statuses
*/
Route::post('/status',[
	'uses' => '\Facenoob\Http\Controllers\StatusController@postStatus',
	'as' => 'status.post',
	'middleware' => ['auth'],
]);
/*
Replies
*/
Route::post('/status/{statusId}/comment',[
	'uses' => '\Facenoob\Http\Controllers\StatusController@postComment',
	'as' => 'status.comment',
	'middleware' => ['auth'],
]);
/*
Likes
*/
Route::get('/status/{statusId}/like', [
	'uses' => '\Facenoob\Http\Controllers\StatusController@getLike',
	'as' => 'status.like',
	'middleware' => ['auth'],
]);