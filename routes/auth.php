<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\PhoneController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // 2FA Management (Profile)
    Route::controller(TwoFactorController::class)->prefix('/2fa')->name('2fa.')->group(function () {
        Route::post('/enable', 'enable')->name('enable');
        Route::patch('/update', 'updateChannel')->name('update');
        Route::post('/disable', 'disable')->name('disable');
    });

    // Phone Management
    Route::controller(PhoneController::class)->prefix('/phone')->name('phone.')->group(function () {
        Route::post('/update', 'update')->name('update');
        Route::post('/send-otp', 'sendOtp')->name('send_otp')->middleware('throttle:1,1');
        Route::post('/verify', 'verify')->name('verify');
    });
    
});

// 2FA Challenge (Login Flow) 
Route::controller(TwoFactorController::class)->prefix('/2fa')->name('2fa.')->middleware(['guest', '2fa.session'])->group(function () {
    Route::get('/show', 'show')->name('show');
    Route::post('/verify', 'verify')->name('verify');
    Route::post('/resend', 'resend')->middleware('throttle:1,1')->name('resend');
});
