<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'digging_deeper',], function(){
    Route::get('collections', [App\Http\Controllers\DiggingDeeperController::class, 'collections'])
        ->name('digging_deeper.collections');
    Route::get('process-video', [App\Http\Controllers\DiggingDeeperController::class, 'processVideo'])
        ->name('digging_deeper.processVideo');
    Route::get('prepare-catalog', [App\Http\Controllers\DiggingDeeperController::class, 'prepareCatalog'])
        ->name('digging_deeper.prepareCatalog');

});

Route::group(['namespace' => 'App\Http\Controllers\Blog', 'prefix' => 'blog'], function () {
    Route::resource('posts', 'PostController')->names('blog.posts');
});

//Route::resource('rest', 'RestTestController')->names('restTest');

//Админка Блога
$groupData = [
    'namespace' => 'App\Http\Controllers\Blog\Admin',
    'prefix' => 'admin/blog',
];
Route::group($groupData, function() {
    //BlogCategory
    $methods = ['index', 'update', 'create', 'edit', 'store',];
    Route::resource('categories', 'CategoryController')
        ->only($methods)
        ->names('blog.admin.categories');

    //BlogPost
    Route::resource('posts', 'PostController')
        ->except(['show'])
        ->names('blog.admin.posts');

    Route::get('/posts/{post}/restore', 'PostController@restore')->name('blog.admin.posts.restore');
});
