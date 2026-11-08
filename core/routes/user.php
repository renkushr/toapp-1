<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.submit');
            Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
        });

        Route::controller('RegisterController')->middleware(['guest'])->group(function () {
            Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register')->name('register.submit');
            Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
        });


        

        

      
        // Avoid URLs with "password/reset" — some WAFs / proxies block them with 403.
        Route::controller('ForgotPasswordController')->prefix('account-recovery')->name('password.')->group(function () {
            Route::get('/', 'showLinkRequestForm')->name('request');
            Route::redirect('/email', '/user/account-recovery', 301);
            Route::post('/email', 'sendResetCodeEmail')->name('email');
            Route::get('/code-verify', 'codeVerify')->name('code.verify');
            Route::post('/verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->prefix('account-recovery')->name('password.')->group(function () {
            Route::post('/update', 'reset')->name('update');
            Route::get('/new/{token}', 'showResetForm')->name('reset');
        });

        Route::redirect('password/reset', '/user/account-recovery', 301);
        Route::redirect('forgot-password', '/user/account-recovery', 301);
        Route::get('password/reset/{token}', function (string $token) {
            return redirect()->route('user.password.reset', $token);
        });
        Route::get('forgot-password/{token}', function (string $token) {
            return redirect()->route('user.password.reset', $token);
        });
        Route::get('forgot-password/new/{token}', function (string $token) {
            return redirect()->route('user.password.reset', $token);
        });

        Route::controller('SocialiteController')->group(function () {
            Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
            Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
        });
    });
});

//step register user data
Route::name('user.')->group(function(){
    Route::middleware('registration.check.step')->group(function(){
        Route::get('user-data', 'User\UserController@userData')->name('data');
        Route::get('register3', 'User\UserController@register3')->name('register3');
    });
    
    Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');
    Route::post('register3-submit', 'User\Auth\RegisterController@registerSubmit')->name('register3.submit');
});

Route::middleware('auth')->name('user.')->group(function () {
    Route::middleware('registration.complete')->namespace('User')->controller('UserController')->group(function () {
        Route::get('approval-pending', 'approvalPending')->name('approval.pending');
    });

    //authorization
    Route::middleware('registration.complete')->namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::match(['get', 'post'], 'resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
    });

    Route::middleware(['check.status', 'registration.complete'])->group(function () {

        Route::namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');

                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');

                //Setting
                Route::get('setting', 'setting')->name('setting');

                   //Support
                Route::get('call', 'call')->name('call');

                 //withdraw
                 Route::get('withdrawsuccess', 'withdrawSuccess')->name('withdrawsuccess');
                 Route::get('withdrawunsuccess', 'withdrawUnsuccess')->name('withdrawunsuccess');

                Route::post('/investment', 'investment')->name('investment');
                Route::get('/investment/log', 'investmentLog')->name('investment.log');
                
                Route::get('referrals', 'referrals')->name('referrals');
                Route::post('level/count', 'levelCount')->name('level.count');
            });

            // Plans — insurance quote flow (same as Check Insure)
            Route::controller('InsuranceQuoteController')->group(function () {
                Route::get('/plans', 'entry')->name('plans');
                Route::prefix('plans')->name('plans.')->group(function () {
                    Route::get('/my-packages', 'myPackages')->name('mine');
                    Route::get('/profile', 'profile')->name('profile');
                    Route::post('/profile', 'saveProfile')->name('profile.submit');
                    Route::get('/survey', 'survey')->name('survey');
                    Route::post('/survey', 'saveSurvey')->name('survey.submit');
                    Route::get('/results', 'results')->name('results');
                    Route::get('/products/{plan}', 'showPlan')->name('products.show');
                    Route::post('/products/{plan}/select', 'selectPlan')->name('products.select');
                    Route::get('/applications/{application}/terms', 'terms')->name('terms');
                    Route::post('/applications/{application}/terms', 'confirmTerms')->name('terms.confirm');
                    Route::get('/applications/{application}/waiting-agent', 'waiting')->name('waiting');
                    Route::get('/applications/{application}/chat', 'chat')->name('chat');
                    Route::post('/applications/{application}/chat', 'sendChat')->name('chat.send');
                    Route::get('/applications/{application}/conditions', 'editConditions')->name('conditions.edit');
                    Route::post('/applications/{application}/conditions', 'updateConditions')->name('conditions.update');
                });
            });

            Route::controller('AgentChatController')->group(function () {
                Route::get('agent-chat', 'index')->name('agent.chat');
                Route::post('agent-chat', 'send')->name('agent.chat.send');
                Route::get('agent-chat/messages', 'messages')->name('agent.chat.messages');
                Route::get('agent-chat/unread', 'unreadSummary')->name('agent.chat.unread');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
        Route::post('profile-setting', 'submitProfile')->name('profile.setting.update');
                Route::get('change-password', 'changePassword')->name('change.password');
        Route::post('change-password', 'submitPassword')->name('change.password.update');

                Route::get('bank-setting', 'bank')->name('bank.setting');
        Route::post('bank-setting', 'submitBank')->name('bank.setting.update');
            });


            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw.')->group(function () {
                Route::middleware('kyc')->group(function () {
                    Route::get('/', 'withdrawMoney')->name('index');
                    Route::post('/', 'withdrawStore')->name('money');
                    Route::get('preview', 'withdrawPreview')->name('preview');
                    Route::post('preview', 'withdrawSubmit')->name('submit');
                });
                Route::get('history', 'withdrawLog')->name('history');
            });
        });

        // Payment
        Route::prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
            Route::any('/', 'deposit')->name('index');
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
