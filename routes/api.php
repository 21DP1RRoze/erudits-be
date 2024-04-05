<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuizInstanceController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group([
    'middleware' => 'auth:sanctum',
], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return auth()->user();
    });

    // Version 1 - more verbose
//    Route::apiResource('quizzes', 'App\Http\Controllers\Api\QuizController');
//    Route::prefix('/quizzes/{quiz}')->group(function () {
//        Route::apiResource('question-groups', 'App\Http\Controllers\Api\QuestionGroupController');
//        Route::prefix('/question-groups/{questionGroup}')->group(function () {
//            Route::apiResource('questions', 'App\Http\Controllers\Api\QuestionController');
//            Route::prefix('/questions/{question}')->group(function () {
//                Route::apiResource('answers', 'App\Http\Controllers\Api\AnswerController');
//            });
//        });
//    });

    // Version 2 - more concise
    Route::apiResource('quizzes', 'App\Http\Controllers\Api\QuizController');
    Route::prefix('/quiz-instances')->group(function () {
        Route::get('/public', [QuizInstanceController::class, 'getPublicQuizzes']);
        Route::get('/active', [QuizInstanceController::class, 'getActiveQuizzes']);
        Route::get('/active-public', [QuizInstanceController::class, 'getActivePublicQuizzes']);

        Route::get('{quiz_instance}/players', [QuizInstanceController::class, 'getQuizInstancePlayers']);
    });
    Route::apiResource('question-groups', 'App\Http\Controllers\Api\QuestionGroupController');
    Route::apiResource('questions', 'App\Http\Controllers\Api\QuestionController');
    Route::apiResource('answers', 'App\Http\Controllers\Api\AnswerController');
    Route::post('open-answers', [PlayerController::class, 'storeOpenAnswer']);
    Route::post('quizzes/{quiz}/save', [QuizController::class, 'saveQuiz']);
    Route::post('quizzes/{quiz}/savetitledescription', [QuizController::class, 'saveTitleDescription']);

    Route::post('quiz-instances/set-players-active', [QuizInstanceController::class, 'setAllPlayersActive']);
    Route::post('players/{player}/deactivate', [PlayerController::class, 'setPlayerInactive']);
});
Route::apiResource('quiz-instances', 'App\Http\Controllers\Api\QuizInstanceController');
Route::apiResource('players', 'App\Http\Controllers\Api\PlayerController');

Route::get('/quiz-instances/{quiz_instance}/active-question-group', [QuizInstanceController::class, 'getActiveQuestionGroup']);
Route::post('/quiz-instances/{quiz_instance}/active-question-group', [QuizInstanceController::class, 'setActiveQuestionGroup']);

Route::post('answers/set-selected-answer', [AnswerController::class, 'setSelectedAnswer']);
Route::post('answers/set-open-answer', [AnswerController::class, 'setOpenAnswer']);

Route::post('quiz-instances/{quiz_instance}/poll', [QuizInstanceController::class, 'handleQuestionGroupPoll']);
