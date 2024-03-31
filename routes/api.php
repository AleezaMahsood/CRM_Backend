<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\leads;
use App\Http\Controllers\Api\LeadsController;
use App\Http\Controllers\Auth\ApiAuthController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
Route::post('/register', [ApiAuthController::class, 'register'])->name('register.api');
Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout.api');
Route::get('/enums', [ApiAuthController::class, 'getEnums']);

Route::get('/leads', [LeadsController::class, 'index']);
Route::post('/leads', [LeadsController::class, 'store']);
Route::get('/users',[ApiAuthController::class, 'index']);