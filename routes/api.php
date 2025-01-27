<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\PostController;
use \App\Http\Controllers\JobController;
use \App\Http\Controllers\SearchController;


Route::prefix('/v1/user')->group(function (){
    Route::controller(UserController::class)->group(function (){
        Route::post('/','postUser');
        Route::get('/{id}','getUser');
        Route::get('/{id}/post','getUserPost');
        Route::get('/{id}/job','getUserJob');
        Route::get('/{id}/complete','getAllUserCompleteRequest');
    });
});

Route::prefix('/v1/post')->group(function (){
   Route::controller(PostController::class)->group(function (){
       Route::get('','getAllPost');
       Route::post('','postPost');
       Route::get('/{id}','getPost');
       Route::delete('/{id}','deletePost');
       Route::patch('/{id}/view','updatePostView');
   });
});
Route::prefix('/v1/search')->group(function (){
   Route::controller(SearchController::class)->group(function (){
       Route::get('/user','searchUser');
       Route::get('/job','searchJob');
       Route::get('/post','searchPost');
   });
});
Route::prefix('/v1/job')->group(function (){
   Route::controller(JobController::class)->group(function (){
       Route::get('/', 'getAllJob');
       Route::post('/', 'postJob');

       Route::prefix('/{id}')->group(function () {
           Route::get('/', 'getJob');
           Route::delete('/', 'deleteJob');
           Route::post('/job-contract', 'postJobContract');

           Route::prefix('/complete')->group(function () {
               Route::post('/', 'postJobCompletionRequest');
               Route::get('/', 'getJobCompletionRequest');
               Route::delete('/', 'deleteJobCompletionRequest');
           });

           Route::put('/finish', 'finishJob');
           Route::get('/job-bid', 'getJobBid');
           Route::get('/job-offer', 'getJobOffer');
       });
   });
});

