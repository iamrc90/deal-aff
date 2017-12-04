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

Route::post('/posts/list','PostsController@getPostsData')->name('getPostsData');
Route::post('/posts/updatestatus','PostsController@updateStatus')->name('updatePostStatus');
Route::post('/posts/delete','PostsController@delete')->name('deletePost');
Route::post('/subscribe','SubscriptionController@subscribe')->name('subscribe');
Route::post('/addViewCount','HomeController@addViewCount')->name('addViewCount');
