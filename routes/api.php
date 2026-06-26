<?php

use App\Http\Controllers\Api\BorrowingController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API untuk cek status peminjaman buku mahasiswa
Route::prefix('mahasiswa/{nim}')->group(function () {
    Route::get('borrowing-status', [BorrowingController::class, 'getBorrowingStatus']);
    Route::get('borrowing-history', [BorrowingController::class, 'getBorrowingHistory']);
});
