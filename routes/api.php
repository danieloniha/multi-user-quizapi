<?php

use App\Http\Controllers\AnswersController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SubjectsController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



// Sign Up
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('login', [AuthenticatedSessionController::class, 'store']);
//Route::post('teacher/login', [AuthenticatedSessionController::class, 'store']);
// Login
//Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware(['auth:sanctum', 'user-role:teacher'])->group(function () {
    Route::apiResource('/subjects', SubjectsController::class);
    Route::post('logout', [AuthenticatedSessionController::class, 'logout']);

    Route::controller(QuizController::class)->group(function () {
        Route::get('subjects/{subject}/quiz', 'index');
        Route::post('subjects/{subject}/quiz', 'store');
        Route::get('subjects/{subject}/quizzes/{quiz}', 'show');
        Route::patch('subjects/{subject}/quizzes/{quiz}', 'update');
        Route::delete('subjects/{subject}/quizzes/{quiz}', 'destroy');
    });

    Route::controller(QuestionsController::class)->group(function () {
        Route::get('quizzes/{quiz}/questions', 'index');
        Route::post('quizzes/{quiz}/questions', 'store');
        Route::get('quizzes/{quiz}/questions/{question}', 'show');
        Route::patch('quizzes/{quiz}/questions/{question}', 'update');
        Route::delete('quizzes/{quiz}/questions/{question}', 'destroy');
    });

    Route::controller(AnswersController::class)->group(function (){
        Route::get('questions/{question}/answers', 'index');
        Route::post('questions/{question}/answers', 'store');
        Route::get('questions/{question}/answers/{answer}', 'show');
        Route::patch('questions/{question}/answers/{answer}', 'update');
        Route::delete('questions/{question}/answers/{answer}', 'destroy');
    });

});

Route::middleware(['auth:sanctum', 'user-role:student'])->group(function () {
});
