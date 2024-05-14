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
use App\Http\Middleware\CustomAuthMiddleware;

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

//Route::middleware(['auth:api'])->get('/user-leads', [leadsController::class, 'getUserLeads']);
//Route::middleware('custom.auth')->get('/user-leads', [LeadsController::class, 'getUserLeads']);
Route::get('/user-leads', [LeadsController::class, 'getUserLeads']);

//registration and login routes
//Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
//Route::post('/register', [ApiAuthController::class, 'register'])->name('register.api');
//Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout.api');

//Route::post('login', [ApiAuthController::class, 'login'])->name('auth.login');
Route::group([
    'middleware' => 'custom.auth',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [ApiAuthController::class, 'register'])->WithoutMiddleware('custom.auth');
    Route::post('login', [ApiAuthController::class, 'login'])->WithoutMiddleware('custom.auth');
    Route::post('logout', [ApiAuthController::class, 'logout']);
    Route::post('refresh', [ApiAuthController::class, 'refresh']);
    Route::post('me', [ApiAuthController::class, 'me']);
});
//Enums for project,leads and user
Route::get('/enums',[ApiAuthController::class, 'getEnums']);
Route::get('/Leadenums',[LeadsController::class, 'getEnums']);
Route::get('/Projectenums',[ProjectController::class, 'getEnums']);
//lead routes
Route::get('/leads', [LeadsController::class, 'index']);
Route::post('/leads', [LeadsController::class, 'store']);
Route::get('/user/{userId}/leads', [LeadsController::class, 'countLeadsByStatus']);
//user routes
Route::get('/users',[ApiAuthController::class, 'index']);
//Lead create Routes
Route::get('/leads', [LeadsController::class, 'index']);
Route::post('/leads', [LeadsController::class, 'store']);

//campaign routes
Route::get('/campaigns', [CampaignController::class ,'index']);
Route::post('/campaigns', [CampaignController::class ,'store']);
 
//project routes 
Route::get('/projects', [ProjectController::class ,'index']);
Route::post('/projects',[ ProjectController::class ,'store']);
