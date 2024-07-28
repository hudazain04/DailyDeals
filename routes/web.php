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
//    return view('welcome');
    return "CI / CD by | ****** JAWAD TAKI ALDEEN *******";
});


