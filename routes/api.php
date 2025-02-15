<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\SetController;
use App\Http\Controllers\Api\UserCardController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes publiques pour les cartes et sets
Route::get('/sets', [SetController::class, 'index']);
Route::get('/sets/{set}', [SetController::class, 'show']);
Route::get('/sets/{set}/cards', [SetController::class, 'cards']);
Route::get('/cards', [CardController::class, 'index']);
Route::get('/cards/{card}', [CardController::class, 'show']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Collection
    Route::get('/user/cards', [UserCardController::class, 'index']);
    Route::post('/user/cards', [UserCardController::class, 'store']);
    Route::put('/user/cards/{cardId}', [UserCardController::class, 'update']);
    Route::delete('/user/cards/{cardId}', [UserCardController::class, 'destroy']);

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::put('/wishlist/{cardId}', [WishlistController::class, 'update']);
    Route::delete('/wishlist/{cardId}', [WishlistController::class, 'destroy']);
});
