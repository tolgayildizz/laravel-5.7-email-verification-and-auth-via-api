<?php


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('payload', 'AuthController@payload');
    Route::post('register','Auth\RegisterController@create');
    Route::post('forgot-password-email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('forgot-password-reset', 'Auth\ResetPasswordController@reset');
});


Route::middleware(['auth:api'])->group(function () {
    // Email Verification Routes...
    Route::post('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
    Route::post('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
});

Route::group(['namespace' => 'Profile','prefix'=>'profile'], function () {
    // Current user
    Route::group(['prefix' => 'current', 'middleware' => ['auth:api']], function () {
        Route::post('set-password', 'ProfileController@setPassword');
    });
});
