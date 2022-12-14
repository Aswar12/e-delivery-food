<?php

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FoodGalleryController;
use App\Http\Controllers\TransactionController;

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
    return redirect()->route('dashboard');
});

Route::prefix('dashboard')->middleware('auth:sanctum', config('jetstream.auth_session'),
    'verified','admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('food' , FoodController::class);
    Route::resource('category', FoodCategoryController::class);
    Route::resource('food.gallery', FoodGalleryController::class)->shallow()->only([
        'index', 'create', 'store', 'destroy'
    ]);   

    Route::get('transactions/{id}/status/{status}', [TransactionController::class, 'changeStatus'])->name('transactions.changeStatus');
    Route::resource('transactions' , TransactionController::class);
    });

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified','admin'
// ])->group(function () {
//     Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
//     Route::resource('users', UserController::class);
// });

Route::get('midtrans/success', [MidtransController::class, 'success']);
Route::get('midtrans/unfinish', [MidtransController::class, 'unfinish']);
Route::get('midtrans/error', [MidtransController::class, 'error']);
