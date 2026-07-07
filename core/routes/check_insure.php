<?php

use App\Http\Controllers\CheckInsure\AuthController;
use App\Http\Controllers\CheckInsure\QuoteController;
use Illuminate\Support\Facades\Route;

Route::name('checkinsure.')->group(function () {
    Route::middleware('portal.guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    });

    Route::get('/verify', [AuthController::class, 'showVerify'])->name('verify')->middleware('auth');
    Route::post('/verify', [AuthController::class, 'verify'])->name('verify.submit')->middleware('auth');
    Route::post('/verify/resend', [AuthController::class, 'resendOtp'])->name('verify.resend')->middleware('auth');

    Route::middleware(['portal.auth'])->group(function () {
        Route::get('/profile', [QuoteController::class, 'profile'])->name('profile');
        Route::post('/profile', [QuoteController::class, 'saveProfile'])->name('profile.submit');
        Route::get('/survey', [QuoteController::class, 'survey'])->name('survey');
        Route::post('/survey', [QuoteController::class, 'saveSurvey'])->name('survey.submit');
        Route::get('/results', [QuoteController::class, 'results'])->name('results');
        Route::get('/plans/{plan}', [QuoteController::class, 'showPlan'])->name('plans.show');
        Route::post('/plans/{plan}/select', [QuoteController::class, 'selectPlan'])->name('plans.select');
        Route::get('/applications/{application}/terms', [QuoteController::class, 'terms'])->name('terms');
        Route::post('/applications/{application}/terms', [QuoteController::class, 'confirmTerms'])->name('terms.confirm');
        Route::get('/applications/{application}/chat', [QuoteController::class, 'chat'])->name('chat');
        Route::post('/applications/{application}/chat', [QuoteController::class, 'sendChat'])->name('chat.send');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    Route::redirect('/', '/check-insure/register');
});
