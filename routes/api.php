<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BootcampApiController;
use App\Http\Controllers\Api\MentorApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(BootcampApiController::class)->group(function(){
    Route::get('/bootcamps', 'index');
    Route::get('/bootcamps/detail/{id}', 'detail');
    Route::post('/bootcamp/save', 'addBootcamp');
});

Route::controller(AuthApiController::class)->group(function(){
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::controller(MentorApiController::class)->group(function(){
    Route::get('/mentor', 'mentor');
    Route::post('/add_mentor', 'addMentor');
    Route::put('/update_mentor/{username}', 'updateMentor');
});
