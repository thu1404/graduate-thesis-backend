<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HRController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\HiringProcessController;
use App\Http\Controllers\HiringProcessRoundController;
use App\Http\Controllers\SkillsController;

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


/* Auth */

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    //logout

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::group(['prefix' => 'hr'], function () {
        Route::get('/list-jobs', [HRController::class, 'listJobs']);
        Route::post('/create-job', [HRController::class, 'createJob']);
        Route::post('/edit-job/{id}', [HRController::class, 'editJob']);
        Route::post('/delete-job/{id}', [HRController::class, 'deleteJob']);
        Route::get('/show-job/{id}', [HRController::class, 'showJob']);

        //Phase 2
        Route::post('/disable-job/{id}', [HRController::class, 'disableJob']);
        Route::post('/enable-job/{id}', [HRController::class, 'enableJob']);

        // getKanbanBoard
        Route::get('/job/{id}/process', [HRController::class, 'getKanbanBoard']);
        //change round id
        Route::post('/job/{id}/process/change-round', [HRController::class, 'changeRound']);
        //process/reject
        Route::post('/job/{id}/process/reject', [HRController::class, 'rejectCV']);

        //hirring process group
        Route::group(['prefix' => 'hiring-process'], function () {
            Route::get('/', [HiringProcessController::class, 'index']);
            Route::post('/create', [HiringProcessController::class, 'store']);
            Route::post('/edit/{id}', [HiringProcessController::class, 'edit']);
            Route::post('/delete/{id}', [HiringProcessController::class, 'destroy']);
            Route::get('/show/{id}', [HiringProcessController::class, 'show']);
        });
        //hiring process round group
        Route::group(['prefix' => 'hiring-process-round'], function () {
            Route::get('/', [HiringProcessRoundController::class, 'index']);
            Route::post('/create', [HiringProcessRoundController::class, 'store']);
            Route::post('/edit/{id}', [HiringProcessRoundController::class, 'edit']);
            Route::post('/delete/{id}', [HiringProcessRoundController::class, 'destroy']);
            Route::get('/show/{id}', [HiringProcessRoundController::class, 'show']);
        });
    });

    /* Email */
    Route::group(['prefix' => 'email'], function () {
        Route::post('sendMailAccessCV', [HRController::class, 'sendMailAccessCV']);
    });


    Route::group(['prefix' => 'candidate'], function () {
        Route::get('/profile', [CandidateController::class, 'getProfile'])->name('candidateProfile');
        //Phase 2
        Route::get('/jobs', [CandidateController::class, 'getJob']);
        //Phase 2
        Route::post('/profile', [CandidateController::class, 'storeProfile']);
        //Phase 2
        Route::post('updateProfile/{id}', [CandidateController::class, 'updateProfile']);
        //Phase 2
        Route::post('deleteProfile/{id}', [CandidateController::class, 'deleteProfile']);
        Route::post('applyJobs', [CandidateController::class, 'applyJobs']);
    });


    //Skills
    Route::group(['prefix' => 'skills'], function () {
        Route::get('/', [SkillsController::class, 'index']);
        Route::post('/create', [SkillsController::class, 'store']);
        Route::post('/edit/{id}', [SkillsController::class, 'edit']);
        Route::post('/delete/{id}', [SkillsController::class, 'destroy']);
        Route::get('/show/{id}', [SkillsController::class, 'show']);
    });
});
