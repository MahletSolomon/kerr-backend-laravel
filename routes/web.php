<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    printf(DB::table('users')->get());
    return view('welcome');
});
