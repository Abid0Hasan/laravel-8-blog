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

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('posts', [\App\Http\Controllers\PostController::class, 'index'])->name('post.index');

Route::get('post/{slug}', [\App\Http\Controllers\PostController::class, 'details'])->name('post.details');
Route::get('category/{slug}', [\App\Http\Controllers\PostController::class, 'postByCategory'])->name('category.posts');
Route::get('tag/{slug}', [\App\Http\Controllers\PostController::class, 'postByTag'])->name('tag.posts');

Route::get('profile/{username}', [\App\Http\Controllers\AuthorController::class, 'profile'])->name('author.profile');
Route::post('subscriber', [\App\Http\Controllers\SubscriberController::class, 'store'])->name('subscriber.store');

Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('search');



Auth::routes();

Route::group(['middleware' => ['auth']], function (){
    Route::post('favorite/{post}/add',[ \App\Http\Controllers\FavoriteController::class, 'add' ])->name('post.favorite');
    Route::post('comment/{post}',[ \App\Http\Controllers\CommentController::class, 'store' ])->name('comment.store');
});

Route::group(['as'=>'admin.','prefix'=>'admin', 'middleware'=>['auth','admin']], function (){

    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('profile-update', [\App\Http\Controllers\Admin\SettingsController::class, 'updateProfile'])->name('profile.update');
    Route::put('password-update', [\App\Http\Controllers\Admin\SettingsController::class, 'updatePassword'])->name('password.update');


    Route::resource('tag', '\App\Http\Controllers\Admin\TagController');
    Route::resource('category', '\App\Http\Controllers\Admin\CategoryController');
    Route::resource('post', '\App\Http\Controllers\Admin\PostController');

    Route::put('/post/{id}/approve', [\App\Http\Controllers\Admin\PostController::class, 'approval'])->name('post.approve');
    Route::get('/pending/post', [\App\Http\Controllers\Admin\PostController::class, 'pending'])->name('post.pending');

    Route::get('/favorite',[\App\Http\Controllers\Admin\FavoriteController::class, 'index'] )->name('favorite.index');

    Route::get('authors',[\App\Http\Controllers\Admin\AuthorController::class, 'index'] )->name('author.index');
    Route::delete('authors/{id}',[\App\Http\Controllers\Admin\AuthorController::class, 'destroy'] )->name('author.destroy');

    Route::get('comments',[\App\Http\Controllers\Admin\CommentController::class, 'index'] )->name('comment.index');
    Route::delete('comments/{id}',[\App\Http\Controllers\Admin\CommentController::class, 'destroy'] )->name('comment.destroy');

    Route::get('/subscriber',[\App\Http\Controllers\Admin\SubscriberController::class, 'index'] )->name('subscriber.index');
    Route::delete('/subscriber/{subscriber}',[\App\Http\Controllers\Admin\SubscriberController::class, 'destroy'] )->name('subscriber.destroy');

});

Route::group(['as'=>'author.','prefix'=>'author', 'middleware'=>['auth','author']], function (){

    Route::get('dashboard', [\App\Http\Controllers\Author\DashboardController::class, 'index'])->name('dashboard');

    Route::get('settings', [\App\Http\Controllers\Author\SettingsController::class, 'index'])->name('settings.index');
    Route::put('profile-update', [\App\Http\Controllers\Author\SettingsController::class, 'updateProfile'])->name('profile.update');
    Route::put('password-update', [\App\Http\Controllers\Author\SettingsController::class, 'updatePassword'])->name('password.update');

    Route::get('/favorite',[\App\Http\Controllers\Author\FavoriteController::class, 'index'] )->name('favorite.index');

    Route::get('comments',[\App\Http\Controllers\Author\CommentController::class, 'index'] )->name('comment.index');
    Route::delete('comments/{id}',[\App\Http\Controllers\Author\CommentController::class, 'destroy'] )->name('comment.destroy');


    Route::resource('post', '\App\Http\Controllers\Author\PostController');
});

View::composer('layouts.frontend.partial.footer',function ($view){

    $categories = App\Models\Category::all();
    $view->with('categories', $categories);

});
