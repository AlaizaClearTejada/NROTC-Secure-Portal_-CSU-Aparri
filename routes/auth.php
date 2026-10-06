<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetCodeController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:10,1');

    Route::get('register/verify', [RegisteredUserController::class, 'showVerify'])
        ->name('register.verify');
    Route::post('register/verify', [RegisteredUserController::class, 'verify'])
        ->middleware('throttle:10,1')
        ->name('register.verify.store');
    Route::post('register/verify/resend', [RegisteredUserController::class, 'resend'])
        ->middleware('throttle:3,10')
        ->name('register.verify.resend');

    Route::get('register/password', [RegisteredUserController::class, 'showPassword'])
        ->name('register.password');
    Route::post('register/password', [RegisteredUserController::class, 'storePassword'])
        ->name('register.password.store');

    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('forgot-password/code', [PasswordResetCodeController::class, 'show'])
        ->name('password.otp');

    Route::post('forgot-password/code', [PasswordResetCodeController::class, 'verify'])
        ->middleware('throttle:10,1')
        ->name('password.otp.verify');

    Route::post('forgot-password/code/resend', [PasswordResetCodeController::class, 'resend'])
        ->middleware('throttle:3,10')
        ->name('password.otp.resend');

    Route::get('reset-password', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::post('verify-email', VerifyEmailController::class)
        ->middleware('throttle:10,1')
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
