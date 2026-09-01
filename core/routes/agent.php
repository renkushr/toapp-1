<?php

use App\Http\Controllers\ToappAgent\ApplicationController;
use App\Http\Controllers\ToappAgent\AuthController;
use App\Http\Controllers\ToappAgent\ChatController;
use App\Http\Controllers\ToappAgent\DashboardController;
use App\Http\Controllers\ToappAgent\ReportController as AgentReportController;
use App\Http\Controllers\ToappAgent\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('toapp.agent.guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('toapp.agent')->name('logout');

Route::middleware('toapp.agent')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [AgentReportController::class, 'index'])->name('reports.index');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    Route::get('/chat/unread', [ChatController::class, 'unreadSummary'])->name('chat.unread');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{user}', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/{user}/messages', [ChatController::class, 'messages'])->name('chat.messages');

    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/payment-pending', [ApplicationController::class, 'markPaymentPending'])->name('applications.payment_pending');
    Route::post('/applications/{application}/confirm', [ApplicationController::class, 'confirm'])->name('applications.confirm');
});
