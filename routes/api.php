<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use \App\Http\Controllers\UserController;
Route::prefix('/v1/user')->group(function (){
    Route::controller(UserController::class)->group(function (){
        Route::post('/','postUser');
        Route::get('/{id}','getUser');
        Route::get('/{id}/post','getUserPost');
        Route::get('/{id}/job','getUserJob');
        Route::get('/{id}/complete','getAllUserCompleteRequest');
    });
});
