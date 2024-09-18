<?php

use App\Http\Controllers\Admin\TaskManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\userController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManagerMiddleware;
use App\Http\Middleware\OnlyManagerMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//user Management (Admin only)
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::apiResource('UserManagement',UserManagementController::class);
    Route::post('UserManagement/RestoreUser/{userId}',[UserManagementController::class, 'restoreUser']);
    Route::post('UserManagement/forceDelete/{userId}',[UserManagementController::class, 'forceDelete']);
    Route::post('UserManagement/assign-role/{userId}',[UserManagementController::class , 'assignRole']);
});

//Task Management (Admin And Manager)
Route::middleware([ManagerMiddleware::class])->group(function(){
    Route::apiResource('Task',TaskManagementController::class);
    Route::post('Task/forceDelete/{taskID}',[TaskManagementController::class , 'forceDelete']);
    Route::post('Task/RestoreTask/{taskID}',[TaskManagementController::class , 'restore']);
    Route::get('Task/getSoftDelete',[TaskManagementController::class , 'getSoftDelete']);
    Route::POST('Task/getAllTaskAssignedToUser',[TaskManagementController::class , 'getAllTaskAssignedToUser']);
});

//Assigne Task to User (Manager Only)
Route::middleware([OnlyManagerMiddleware::class])->group(function (){
    Route::post('Task/{TaskId}/assign',[TaskManagementController::class,'assign']);
});

// Ordinary User
Route::post('Task/{TaskId}/changeStatus', [userController::class , 'changeStatusOfTask']);
Route::get('TaskAssigned',[userController::class,'index']);

// public route
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('me', [AuthController::class, 'me']);
