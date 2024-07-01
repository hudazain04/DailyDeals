<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('auth')->group(function (){
    Route::post('register', [AuthController::class, 'Register']);
    Route::post('verifyEmail', [AuthController::class, 'VerifyEmail']);
    Route::post('resendVerifyEmailCode',[AuthController::class,'ResendVerifyEmailCode']);
    Route::post('login', [AuthController::class, 'Login']);
    Route::post('forgetPassword',[AuthController::class, 'ForgetPassword']);
    Route::post('resendForgetPasswordVerifyCode',[AuthController::class, 'ResendForgetPasswordCode']);
    Route::post('forgetPasswordVerify', [AuthController::class, 'ForgetPasswordVerify']);
    Route::post('forgetPasswordChange',[AuthController::class, 'ForgetPasswordChange']);
    Route::post('loginEmployee',[AuthController::class,'LoginEmployee']);

});





Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->group(function (){
        Route::post('changePassword',[AuthController::class,'ChangePassword']);
        Route::post('logout', [AuthController::class, 'Logout']);
    });
});
