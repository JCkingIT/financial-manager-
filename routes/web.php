<?php

use App\Http\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Route;

// Route::get('registrar', [RegisterUserController::class,'index']);

Route::post('singup', [RegisterUserController::class, 'create'])
    ->name('register');
