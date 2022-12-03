<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\VeiculoController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\EmprestimoController;

// Rotas para autenticação de usuários
Route::post('/auth/registrar', [AuthController::class, 'createUsuario']);
Route::post('/auth/login', [AuthController::class, 'loginUsuario']);


// Rotas para usuários autenticados através de Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Rotas para manipulação do usuário autenticado
    Route::controller(UsuarioController::class)->prefix('usuario')->group(function () {
        Route::get('/ver-usuario', 'readPessoal'); 
        Route::get('/emprestimos', 'readEmprestimos');
        Route::put('/editar-pessoal', 'updatePessoal');
        Route::delete('/remover-conta', 'deletePessoal');
    });

    // Rotas para visualização de dados públicos
    Route::get('/veiculo/listar', [VeiculoController::class, 'read']); 
    Route::post('/emprestimo/emprestar', [EmprestimoController::class, 'emprestarVeiculo']); 
    
});


// Rotas para usuários autenticados como admin através de Sanctum
Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {

    // Rotas para gerenciamento de tokens //
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::get('/listar', 'read');
        Route::post('/criar', 'create');
        Route::put('/editar', 'update');
        Route::delete('/remover', 'delete');
        Route::post('/auth/registrar-adm', 'createUsuarioAdmin');
    });

    // Rotas para gerenciamento de usuários
    Route::controller(UsuarioController::class)->prefix('usuario')->group(function () {
        Route::get('/listar', 'read');
        Route::post('/criar', 'create');
        Route::put('/editar', 'update');
        Route::delete('/remover', 'delete');
    });

    // Rotas para gerenciamento de empresas
    Route::controller(EmpresaController::class)->prefix('empresa')->group(function () {
        Route::get('/listar', 'read');
        Route::post('/criar', 'create');
        Route::put('/editar', 'update');
        Route::delete('/remover', 'delete');
    });

    // Rotas para gerenciamento de veículos
    Route::controller(VeiculoController::class)->prefix('veiculo')->group(function () {
        Route::post('/criar', 'create');
        Route::put('/editar', 'update');
        Route::delete('/remover', 'delete');
    });

    // Rotas para gerenciamento de empréstimos
    Route::controller(EmprestimoController::class)->prefix('emprestimo')->group(function () {
        Route::get('/listar', 'read');
        Route::post('/criar', 'create');
        Route::put('/editar', 'update');
        Route::delete('/remover', 'delete');
    });
    
});
