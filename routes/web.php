<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

/*
 * --------------------------------------------------------------------------
 * Posts Routes
 * --------------------------------------------------------------------------
 */

Route::get('search', 'SearchController@post')->name('search.posts');

Route::group(["prefix" => "posts", "middleware" => ["auth"]], function () {
    /** READ **/
    // GET /posts => untuk menampilkan semua post
    Route::get('/', 'PostController@index')->name('posts.index')->withoutMiddleware('auth');
    /** CREATE **/
    // GET /posts/create => untuk menampilkan halaman form new post
    Route::get('/create', 'PostController@create')->name('posts.create');
    // POST /posts/store => untuk mengirimkan data post baru ke database
    Route::post('/store', 'PostController@store');


    /** UPDATE **/
    // GET /posts/{posts:slug}/edit => untuk menampilkan halaman form edit post
    Route::get('/{post:slug}/edit', 'PostController@edit');
    // PATCH /posts/{post:slug}/edit => untuk mengirimkan data post update ke database
    Route::patch('/{post:slug}/edit', 'PostController@update');


    /** DELETE **/
    // DELETE /posts
    Route::delete('/{post:slug}/delete', 'PostController@destroy');

    // GET /posts/{post:slug} => menampilkan halaman satu data post
    Route::get('/{post:slug}', 'PostController@show')->name('posts.show');
});


/*
 * --------------------------------------------------------------------------
 * Categories Routes
 * --------------------------------------------------------------------------
 */

/** GET /categories/{category:slug} => untuk menampilkan 1 (satu) category dengan slug tertentu **/
Route::get('/categories/{category:slug}', 'CategoryController@show')->name('categories.show');



/*
 * --------------------------------------------------------------------------
 * Tags Routes
 * --------------------------------------------------------------------------
 */
Route::get('/tags/{tag:slug}', 'TagController@show')->name('tags.show');

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/login', function () {
    return view('login');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
