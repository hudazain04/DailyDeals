<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FaqController;

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
    

});
});

Route::middleware('auth:sanctum','check.blocked')->group(function () {

    Route::prefix('auth')->group(function (){
        Route::post('changePassword',[AuthController::class,'ChangePassword']);
        Route::post('logout', [AuthController::class, 'Logout']);
        Route::get('get_my_profile',[ProfileController::class,'get_my_profile']);
        Route::post('update_my_profile',[ProfileController::class,'update_my_profile']);
        Route::post('soft_delete_my_account',[ProfileController::class,'soft_delete_my_account']);
        Route::post('hard_delete_my_account',[ProfileController::class,'hard_delete_my_account']);
        Route::get('get_all_admins',[ProfileController::class,'get_all_admins']);
        Route::get('get_all_customers',[ProfileController::class,'get_all_customers']);
        Route::get('get_all_merchants',[ProfileController::class,'get_all_merchants']);
        Route::get('get_all_employees',[ProfileController::class,'get_all_employees']);
        Route::post('update_branch',[BranchController::class ,'update_branch']);
    });
});

Route::middleware(['auth:sanctum','customer','check.blocked'])->group(function () {
    Route::get('list_visible_stores',[StoreController::class ,'list_visible_stores']); 
    Route::get('list_customer_branches',[BranchController::class ,'list_customer_branches']);
});

Route::middleware(['auth:sanctum','merchant','check.blocked'])->group(function () {
    Route::post('add_store',[StoreController::class ,'add_store']);
    Route::post('update_store',[StoreController::class ,'update_store']);
    Route::post('delete_store',[StoreController::class ,'delete_store']);
    Route::get('list_merchant_stores',[StoreController::class ,'list_merchant_stores']);
    Route::post('create_branch',[BranchController::class ,'create_branch']);
    Route::post('delete_branch',[BranchController::class ,'delete_branch']);
    Route::get('list_merchant_branches',[BranchController::class ,'list_merchant_branches']);
    
});

Route::middleware(['auth:sanctum','employee','check.blocked'])->group(function () {
    Route::get('list_employee_branches',[BranchController::class ,'list_employee_branches']);

});

Route::middleware(['auth:sanctum','admin','check.blocked'])->group(function ()
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


