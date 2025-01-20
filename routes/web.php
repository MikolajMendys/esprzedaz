<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('pets', PetController::class);
Route::post('/pet/{id}/uploadImage', [PetController::class, 'uploadImage'])->name('pet.uploadImage');