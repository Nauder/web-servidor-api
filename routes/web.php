<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestController;

// Rota para erro 400
Route::view('/erro_de_requisicao', '400_page')->name('400');

Route::get('/', [TestController::class, 'gerarPostTeste']);

Route::middleware('auth:sanctum')->group(function () {
    
});