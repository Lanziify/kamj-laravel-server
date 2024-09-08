<?php

use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuizCodeController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\TopicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Redirect;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('/register',  [AuthController::class, 'register']);
    Route::post('/login',  [AuthController::class, 'login']);
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        // return ['message' => 'Verification link sent!'];
        return Redirect::to('http://localhost:5173/login');
    })->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');
    Route::apiResource('/lessons',  LessonController::class);
    Route::apiResource('/topics',  TopicController::class);
    Route::get('/quizzes/total', [QuizController::class, 'total']);
    Route::apiResource('/quizzes/codes',  QuizCodeController::class);
    Route::get('/quizzes/{id}/codes',  [QuizController::class, 'codes']);
    Route::get('/quizzes/{id}/items',  [QuizController::class, 'items']);
    Route::apiResource('/quizzes',  QuizController::class);
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'v1'], function () {
    Route::get('/user',  [AuthController::class, 'user']);
    Route::get('/refresh',  [AuthController::class, 'refresh']);
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::post('/quizzes/{id}/items',  [QuestionController::class, 'store']);
    Route::apiResource('/quizzes/items',  QuestionController::class);
});
