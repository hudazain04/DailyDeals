<?php

use App\Http\Controllers\AppInformationController;
use App\Http\Controllers\CategoryRequestController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EmployeeMiddleware;
use App\Http\Middleware\MerchantMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\RateController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['check.blocked'])->group(function () {

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
        Route::post('restore_my_account',[ProfileController::class,'restore_my_account']);
        Route::get('get_faq',[FaqController::class ,'get_faq']);
        Route::post('store_byID',[StoreController::class ,'store_byID']);
        Route::post('Branch_byID',[BranchController::class ,'Branch_byID']);

    });
});

Route::middleware('auth:sanctum','check.blocked')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('changePassword', [AuthController::class, 'ChangePassword']);
        Route::post('logout', [AuthController::class, 'Logout']);
        Route::get('get_my_profile', [ProfileController::class, 'get_my_profile']);
        Route::post('update_my_profile', [ProfileController::class, 'update_my_profile']);
        Route::post('soft_delete_my_account', [ProfileController::class, 'soft_delete_my_account']);
        Route::post('hard_delete_my_account', [ProfileController::class, 'hard_delete_my_account']);
        Route::get('get_all_admins', [ProfileController::class, 'get_all_admins']);
        Route::get('get_all_customers', [ProfileController::class, 'get_all_customers']);
        Route::get('get_all_merchants', [ProfileController::class, 'get_all_merchants']);
        Route::get('get_all_employees', [ProfileController::class, 'get_all_employees']);
        Route::get('get_merchant_detail', [ProfileController::class, 'get_merchant_detail']);
        Route::post('update_branch', [BranchController::class, 'update_branch']);
    });


    Route::prefix('app_info')->group(function () {
        Route::middleware('Admin')->group(function () {
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


    Route::prefix('category')->group(function () {
        Route::middleware('roles:Merchant,Employee')->group(function () {
            Route::post('addCategoryRequest', [CategoryRequestController::class, 'AddCategoryRequest']);
            Route::post('updateCategoryRequest/{request_id}', [CategoryRequestController::class, 'UpdateCategoryRequest']);
            Route::get('deleteCategoryRequest/{request_id}', [CategoryRequestController::class, 'DeleteCategoryRequest']);
            Route::get('getAllForUser', [CategoryRequestController::class, 'GetAllForUser']);

        });
        Route::middleware('Admin')->group(function () {
            Route::post('acceptCategoryRequest/{request_id}', [CategoryRequestController::class, 'AcceptCategoryRequest']);
            Route::get('rejectCategoryRequest/{request_id}', [CategoryRequestController::class, 'RejectCategoryRequest']);
            Route::get('getAll', [CategoryRequestController::class, 'GetAll']);

        });

        Route::get('getCategoryRequest/{request_id}', [CategoryRequestController::class, 'GetCategoryRequest'])->middleware('roles:Merchant,Employee,Admin');


    });



    Route::prefix('verification')->group(function () {
        Route::middleware('Admin')->group(function () {
            Route::get('acceptVerificationRequest/{request_id}', [VerificationController::class, 'AcceptVerificationRequest']);
            Route::get('rejectVerificationRequest/{request_id}', [VerificationController::class, 'RejectVerificationRequest']);
            Route::get('getAll', [VerificationController::class, 'GetAll']);

        });
        Route::middleware('Merchant')->group(function () {
            Route::post('addVerificationRequest', [VerificationController::class, 'AddVerificationRequest']);
            Route::get('getAllForUser', [VerificationController::class, 'GetAllForUser']);
        });
        Route::get('getVerificationRequest/{request_id}', [VerificationController::class, 'GetVerificationRequest'])->middleware('roles:Merchant,Admin');

    });


    Route::prefix('offer')->group(function (){
        Route::middleware('role:Merchant,Employee')->group(function (){
            Route::post('addOfferTypeRequest',[OfferController::class,'AddOfferTypeRequest']);
            Route::post('updateOfferTypeRequest/{request_id}',[OfferController::class,'UpdateOfferTypeRequest']);
            Route::get('deleteOfferTypeRequest/{request_id}',[OfferController::class,'DeleteOfferTypeRequest']);
            Route::get('getAllForUser',[OfferController::class,'GetAllForUser']);

        });

        Route::get('getAll',[OfferController::class,'GetAll'])->middleware('Admin');

    });



    Route::prefix('comment')->group(function (){
        Route::middleware('Customer')->group(function (){
            Route::post('addComment',[OfferController::class,'AddComment']);
            Route::post('updateComment/{comment_id}',[OfferController::class,'UpdateComment']);
            Route::get('deleteComment/{comment_id}',[OfferController::class,'DeleteComment']);

        });

        Route::get('getAllCommentsOnOffer/{offer_id}',[OfferController::class,'GetAllCommentsOnOffer']);

    });
    Route::prefix('rate')->group(function (){
        Route::post('addRate',[RateController::class,'AddRate']);
        Route::post('getBranchRates/{branch_id}',[RateController::class,'GetBranchRates']);
        Route::post('getBranchQRs/{branch_id}',[RateController::class,'GetBranchQRs']);


    });

});

    Route::middleware(['auth:sanctum','Customer','check.blocked'])->group(function () {
    Route::get('list_visible_stores',[StoreController::class ,'list_visible_stores']);
    Route::get('list_customer_branches',[BranchController::class ,'list_customer_branches']);
});

Route::middleware(['auth:sanctum','Merchant','check.blocked'])->group(function () {
    Route::post('add_store',[StoreController::class ,'add_store']);
    Route::post('update_store',[StoreController::class ,'update_store']);
    Route::post('delete_store',[StoreController::class ,'delete_store']);
    Route::get('list_merchant_stores',[StoreController::class ,'list_merchant_stores']);
    Route::post('create_branch',[BranchController::class ,'create_branch']);
    Route::post('delete_branch',[BranchController::class ,'delete_branch']);
    Route::get('list_merchant_branches',[BranchController::class ,'list_merchant_branches']);
    Route::post('create_employee',[EmployeeController::class ,'create_employee']);
    Route::post('update_employee',[EmployeeController::class ,'update_employee']);
    Route::post('create_new_code',[EmployeeController::class ,'create_new_code']);
    Route::post('delete_employee',[EmployeeController::class ,'delete_employee']);
    Route::get('get_employees_by_store',[EmployeeController::class ,'get_employees_by_store']);
});

Route::middleware(['auth:sanctum','Employee','check.blocked'])->group(function () {
    Route::get('list_employee_branches',[BranchController::class ,'list_employee_branches']);

});

Route::middleware(['auth:sanctum','Admin','check.blocked'])->group(function ()
{
    Route::post('soft_delete_users_accounts',[ProfileController::class,'soft_delete_users_accounts']);
    Route::post('restore_users_accounts',[ProfileController::class,'restore_users_accounts']);
    Route::post('hard_delete_users_accounts',[ProfileController::class,'hard_delete_users_accounts']);
    Route::post('block',[ProfileController::class,'block']);
    Route::post('unblock',[ProfileController::class,'unblock']);
    Route::post('add_faq',[FaqController::class ,'add_faq']);
    Route::post('update_faq',[FaqController::class ,'update_faq']);
    Route::post('delete_faq',[FaqController::class ,'delete_faq']);
    Route::get('list_all_stores',[StoreController::class ,'list_all_stores']);
    Route::get('list_admin_branches',[BranchController::class ,'list_admin_branches']);
});






