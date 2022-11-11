<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/cadastrar-questao', [\App\Http\Controllers\QuestaoController::class, 'cadastrarQuestao']);
Route::post('/inativar-questao', [\App\Http\Controllers\QuestaoController::class, 'inativarQuestao']);

Route::post('/enviar-resposta', [\App\Http\Controllers\RespostaController::class, 'enviarResposta']);
Route::get('/get-media-geral', [\App\Http\Controllers\RespostaController::class, 'getMediaGeral']);

Route::get('/{id_user}/get-questoes/{id_questao}', [\App\Http\Controllers\RespostaController::class, 'listarQuestoes']);
