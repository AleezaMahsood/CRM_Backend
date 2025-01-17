<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Api\CampaignController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\leads;
use App\Http\Controllers\Api\LeadsController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PerformanceController;
use App\Http\Controllers\Api\AdminPerformanceController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Models\Campaigns;
use App\Models\Projects;
use App\Http\Middleware\CustomAuthMiddleware;
use Illuminate\Support\Facades\Mail; 
use App\Mail\TestEmail;

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

    // Only logged-in users can update their profile
     Route::put('user/{id}', [ApiAuthController::class, 'updateUser']);
     Route::get('user/{id}', [ApiAuthController::class, 'showUser']); 
});
//Enums for project,leads and user
Route::get('/enums',[ApiAuthController::class, 'getEnums']);
Route::get('/Leadenums',[LeadsController::class, 'getEnums']);
Route::get('/Projectenums',[ProjectController::class, 'getEnums']);
//leads updation
Route::get('/user/{userId}/lead', [LeadsController::class, 'userLeads']);
Route::put('/leads/{id}', [LeadsController::class, 'update']);

//User performance routes
Route::get('/user/{userId}/leads', [PerformanceController::class, 'countLeadsByStatus']);
Route::get('/user/{userId}/performance', [PerformanceController::class, 'fetchLeadsByAllStatuses']);

//Admin performance routes
Route::get('/admin/performance', [PerformanceController::class, 'evaluateUserPerformance']);
Route::get('/admin/leads-graph', [AdminPerformanceController::class, 'leadsGraph']);
Route::get('/admin/projects-graph',[AdminPerformanceController::class,'getProjectStats']);
Route::get('/admin/stats',[AdminPerformanceController::class,'getLeadStatistics']);

//user routes
Route::get('/users',[ApiAuthController::class, 'index']);
//Lead create Routes
Route::get('/leads', [LeadsController::class, 'index']);
// User creating leads
Route::post('/leads', [LeadsController::class, 'store']);
// Admin creating leads
Route::post('/leads/admin', [LeadsController::class, 'adminStore']);
//updating leads
Route::put('/leads/{id}', [LeadsController::class, 'update']);
//fetching specific leads data
Route::get('/leads/{id}', [LeadsController::class, 'show']);
Route::post('/chatbot/leads',[LeadsController::class,'storeFromChatbot']);


//campaign routes
Route::get('/campaigns', [CampaignController::class ,'index']);
Route::post('/campaigns', [CampaignController::class ,'store']);
Route::post('/campaigns/check-campaign-name', [CampaignController::class, 'checkCampaignName']);
 
//project routes 
Route::get('/projects', [ProjectController::class ,'index']);
Route::post('/projects',[ ProjectController::class ,'store']);
Route::post('/projects/check-project-name', [ProjectController::class, 'checkProjectName']);



//Route::get('/send-test-email', function () {
//    $campaign = Campaigns::findOrFail(1);

 //   Mail::to('nimo.khan191@gmail.com')->send(new TestEmail($campaign));

    // return 'Campaign email sent!';
// });
