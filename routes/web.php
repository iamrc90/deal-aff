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


Route::get('/','HomeController@index')->name('home');
Route::get('/category/{category_slug}','HomeController@index')->name('categorywise_posts');
Route::get('/deal/{post_slug}','HomeController@viewDeal')->name('viewDeal');
Route::get('/deals/search','HomeController@search')->name('search');
Route::get('/contact-us','HomeController@contactUs')->name('contactForm');
Route::get('/terms-of-service','HomeController@termsOfService')->name('terms');
Route::post('/form-submit','HomeController@postContactUs')->name('postForm');
// Auth::routes();
// // Authentication Routes...
//     $this->get('login', 'Auth\AuthController@showLoginForm');
//     $this->post('login', 'Auth\AuthController@login');
//     $this->get('logout', 'Auth\AuthController@logout');

//     // Registration Routes...
//     $this->get('register', 'Auth\AuthController@showRegistrationForm');
//     $this->post('register', 'Auth\AuthController@register');

//     // Password Reset Routes...
//     $this->get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
//     $this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
//     $this->post('password/reset', 'Auth\PasswordController@reset');

// admin routes
Route::prefix('admin')->group(function () {
	Route::get('/cmc-deal-admin-8253', 'Auth\LoginController@showLoginForm')->name('admin');
	Route::post('/login', 'Auth\LoginController@login')->name('login');
	Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
	// post controller
	Route::middleware('auth')->get('/posts/add','PostsController@addPostForm')->name('addPostForm');
	Route::middleware('auth')->post('/posts/save','PostsController@savePost')->name('savePost');
	Route::middleware('auth')->get('/posts/list','PostsController@listPosts')->name('listPosts');
	//update post
	Route::middleware('auth')->get('/posts/edit/{id}','PostsController@editPostForm')->name('editPostForm');
	Route::middleware('auth')->post('/posts/update','PostsController@updatePost')->name('updatePost');


	Route::middleware('auth')->get('/posts/view/{id}','PostsController@viewPost')->name('viewPost');



	Route::middleware('auth')->get('/posts/category','CategoriesController@addCategoryForm')->name('addCategoryForm');
	Route::middleware('auth')->post('/posts/category/save','CategoriesController@saveCategory')->name('saveCategory');



	Route::get('/dashboard',function() {
		return View('home');
	})->middleware('auth');
});


