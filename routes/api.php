<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ParticipanteController;

Route::post('/cadastrar', [ParticipanteController::class, 'cadastrar']);
Route::post('/login', [ParticipanteController::class, 'login']);
Route::post('/admin-login', [ParticipanteController::class, 'adminLogin']);
Route::post('/comprar', [ParticipanteController::class, 'comprar']);
Route::get('/numeros-comprados', [ParticipanteController::class, 'numerosComprados']);
Route::get('/total-numeros', [ParticipanteController::class, 'totalNumeros']);
Route::get('/buscar-participante', [ParticipanteController::class, 'buscarParticipante']);
Route::delete('/limpar-rifa', [ParticipanteController::class, 'limparRifa']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
