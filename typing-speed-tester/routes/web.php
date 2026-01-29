<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TypingController;

Route::get('/', function () {
    return view('start');
});

Route::post('/start-test', [TypingController::class, 'startTest']);

Route::post('/save', [TypingController::class, 'saveResult']);

Route::get('/leaderboard', [TypingController::class, 'leaderboard'])
     ->name('leaderboard');

Route::get('/get-new-sentence', [TypingController::class, 'getNewSentence']);