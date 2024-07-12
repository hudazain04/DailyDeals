<?php

use App\Http\Controllers\AppInformationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryRequestController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EmployeeMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\MerchantMiddleware;


Route::get('/', function () {
    return view('welcome');
});



Route::prefix('auth')->group(function (){

        Route::post('register', [AuthController::class, 'Register']);
        Route::post('verifyEmail', [AuthController::class, 'VerifyEmail']);
        Route::post('resendVerifyEmailCode',[AuthController::class,'ResendVerifyEmailCode']);
        Route::post('login', [AuthController::class, 'Login']);
        Route::post('forgetPassword',[AuthController::class, 'ForgetPassword']);
        Route::post('resendForgetPasswordVerifyCode',[AuthController::class, 'ResendForgetPasswordCode']);
        Route::post('forgetPasswordVerify', [AuthController::class, 'ForgetPasswordVerify']);
        Route::post('forgetPasswordChange',[AuthController::class, 'ForgetPasswordChange']);


    Route::post('loginEmployee',[AuthController::class,'LoginEmployee'])->middleware(EmployeeMiddleware::class);

});



Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->group(function (){
        Route::post('changePassword',[AuthController::class,'ChangePassword']);
        Route::post('logout', [AuthController::class, 'Logout']);

    });


    Route::prefix('app_info')->group(function (){
        Route::middleware(AdminMiddleware::class)->group(function (){
            Route::post('addOrUpdatePrivacyPolicy', [AppInformationController::class, 'AddORUpdatePrivacyPolicy']);
            Route::get('deletePrivacyPolicy', [AppInformationController::class, 'DeletePrivacyPolicy']);
            Route::post('addOrUpdateTermsAndConditions', [AppInformationController::class, 'AddORUpdateTermsAndConditions']);
            Route::get('deleteTermsAndConditions', [AppInformationController::class, 'DeleteTermsAndConditions']);
            Route::post('addOrUpdateAboutApp', [AppInformationController::class, 'AddORUpdateAboutApp']);
            Route::get('deleteAboutApp', [AppInformationController::class, 'DeleteAboutApp']);

        });
        Route::get('showPrivacyPolicy', [AppInformationController::class, 'ShowPrivacyPolicy']);
        Route::get('showTermsAndConditions', [AppInformationController::class, 'ShowTermsAndConditions']);
        Route::get('showAboutApp', [AppInformationController::class, 'ShowAboutApp']);

    });


    Route::prefix('category')->group(function (){
        Route::middleware('roles:[Merchant,Employee]')->group(function (){
            Route::post('addCategoryRequest', [CategoryRequestController::class, 'AddCategoryRequest']);
            Route::post('updateCategoryRequest/{request_id}', [CategoryRequestController::class, 'UpdateCategoryRequest']);
            Route::get('deleteCategoryRequest/{request_id}', [CategoryRequestController::class, 'DeleteCategoryRequest']);
            Route::get('getAllForUser', [CategoryRequestController::class, 'GetAllForUser']);

        });
        Route::middleware(AdminMiddleware::class)->group(function (){
            Route::post('acceptCategoryRequest/{request_id}', [CategoryRequestController::class, 'AcceptCategoryRequest']);
            Route::get('rejectCategoryRequest/{request_id}', [CategoryRequestController::class, 'RejectCategoryRequest']);
            Route::get('getAll', [CategoryRequestController::class, 'GetAll']);

        });

        Route::get('getCategoryRequest/{request_id}', [CategoryRequestController::class, 'GetCategoryRequest']);


    });


    Route::prefix('verification')->group(function (){
        Route::middleware(AdminMiddleware::class)->group(function (){
            Route::get('acceptVerificationRequest/{request_id}', [VerificationController::class, 'AcceptVerificationRequest']);
            Route::get('rejectVerificationRequest/{request_id}', [VerificationController::class, 'RejectVerificationRequest']);
            Route::get('getAll', [VerificationController::class, 'GetAll']);

        });
        Route::middleware(MerchantMiddleware::class)->group(function (){
            Route::post('addVerificationRequest', [VerificationController::class, 'AddVerificationRequest']);
            Route::get('getAllForUser', [VerificationController::class, 'GetAllForUser']);
        });
        Route::get('getVerificationRequest/{request_id}', [VerificationController::class, 'GetVerificationRequest'])->middleware('roles[Merchant,Admin]');

    });




});
