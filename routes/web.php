<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
Route::get('/quizzes/{quiz?}', [QuizController::class, 'show'])->name('quizzes.show');
Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
Route::post('/quizzes/questions', [QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
Route::get('/quizzes/{id?}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
Route::put('/quizzes/{quiz?}', [QuizController::class, 'update'])->name('quizzes.update');
Route::get('/quizzes/{quiz}/questions/{questionIndex}', [QuizController::class, 'showQuestion'])->name('quizzes.questions.show');
Route::post('/quizzes/{quizId}/questions/{questionId}/answer', [QuizController::class, 'submitAnswer'])->name('quizzes.questions.answer');
Route::get('/quizzes/create-with-ai/form', [QuizController::class, 'createQuizWithAI'])->name('quizzes.createWithAI');
Route::post('/quizzes/create-with-ai', [QuizController::class, 'storeQuizWithAI'])->name('quizzes.storeWithAI');

