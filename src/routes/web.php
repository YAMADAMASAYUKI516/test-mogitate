<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

// 一覧画面・検索/ソート
Route::get('/products', [ProductController::class, 'index'])->name('index');
Route::get('/products/search', [ProductController::class, 'index'])->name('products.search');

// 登録画面・登録処理
Route::get('/products/register', [ProductController::class, 'create'])->name('products.register');
Route::post('/products/register', [ProductController::class, 'store'])->name('products.store');