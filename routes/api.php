<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Api\CampaignController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\leads;
use App\Http\Controllers\Api\LeadsController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Models\Campaigns;
use App\Models\Projects;

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
//registration and login routes
Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
Route::post('/register', [ApiAuthController::class, 'register'])->name('register.api');
Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout.api');
Route::get('/enums', [ApiAuthController::class, 'getEnums']);

//lead routes
Route::get('/leads', [LeadsController::class, 'index']);
Route::post('/leads', [LeadsController::class, 'store']);

//user routes
Route::get('/users',[ApiAuthController::class, 'index']);

//campaign routes
Route::get('/campaigns', [CampaignController::class ,'index']);
Route::post('/campaigns', [CampaignController::class ,'store']);
 
//project routes 
Route::get('/projects', [ProjectController::class ,'index']);
Route::post('/projects',[ ProjectController::class ,'store']);
