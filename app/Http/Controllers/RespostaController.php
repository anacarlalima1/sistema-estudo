<?php

namespace App\Http\Controllers;

use App\Models\Questoes;
use App\Models\Resposta;
use App\Models\User;
use Illuminate\Http\Request;

class RespostaController extends Controller
{
    public function listarQuestoes($id_user, $id_questao)
    {
        try {

            $questao = Questoes::select('questoes.*')->where('questoes.id', $id_questao)->first();

            if (!isset($questao)) {
                return response()->json(['success' => false, 'message' => 'Questão não encontrada'], 500);
            }

            $questao->usuarios = User::select('users.id', 'users.nome')->get();

            $respostas = Resposta::where('id_user', $id_user)->where('id_questao', $questao->id)->get();

            foreach ($questao->usuarios as $u) {
                $resposta = $respostas->where('id_user', $u->id)->first();
                $u->resposta = isset($resposta) ? $resposta->alternativa : false;
            }

            return response()->json(['success' => true, 'questao' => $questao]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    public function enviarResposta(Request $request)
    {
        try {
            $resposta = Resposta::where('id_user', $request['id_user'])
                ->where('id_questao', $request['id_questao'])->first();

            $dados['alternativa'] = $request['alternativa'];

            $gabarito = Questoes::where('id', $request['id_questao'])->first()->rgabarito;

            $dados['gabarito'] = $gabarito;
            $dados['resultado'] = $gabarito == $request['alternativa'] ? 'Certo' : 'Errado';

            if (isset($resposta)) {
                if ($resposta->alternativa == $request['alternativa']) {

                }
                return response()->json(['success' => true, 'alternativa' => "Questão já respondida!"]);
            } else {
                $dados['id_user'] = $request['id_user'];
                $dados['id_questao'] = $request['id_questao'];
                $dados['created_at'] = now();
                $arrayDados[] = $dados;
                Resposta::insert($arrayDados);
            }

            return response()->json(['success' => true, 'alternativa' => $request['alternativa']]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    public function getMediaGeral()
    {
        try {
            $questoes = Questoes::select('questoes.id')
                ->where('questoes.status', 'Ativo')
                ->get();

            $ids_user = User::select('id', 'nome')
                ->get();

            $niveis = Questoes::select('questoes.*')
                ->where('questoes.nivel', ['Fácil','Médio', 'Díficil'])
                ->get();

            $respostas_geral = Resposta::where('id_questao', $questoes->pluck('id'))->whereIn('id_user', $ids_user)->get();
            $porcentagem_geral = count($respostas_geral) > 0 ? round(($respostas_geral->where('resultado', 'Certo')->count() / count($respostas_geral)) * 100) : 0;
            $questoes = $this->forPorcentagemQuestoes($questoes, $ids_user, $niveis);

            return response()->json(['success' => true, 'questoes' => $questoes, 'corretas' => $porcentagem_geral, 'incorretas' => (100 - $porcentagem_geral)]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    private function forPorcentagemQuestoes($questoes, $ids_user)
    {
        foreach ($questoes as $q) {

            $niveis = Resposta::join('questoes', 'respostas.id_questao', '=', 'questoes.id')->where('id_questao', $q->id)->whereIn('id_user', $ids_user)->get();
            $respostas = Resposta::where('id_questao', $q->id)->whereIn('id_user', $ids_user)->get();
            $q->qtd_respostas = count($respostas);

            if ($q->qtd_respostas > 0) {
                $q->incorretas = $respostas->where('resultado', 'Errado')->count();
                $q->corretas = $respostas->where('resultado', 'Certo')->count();
                $q->porcentagem_corretas = round(($q->corretas / $q->qtd_respostas) * 100);
                $q->porcentagem_incorretas = round(($q->incorretas / $q->qtd_respostas) * 100);
                $q->porcentagem_facil = $niveis->where('nivel','Fácil')->where('resultado', 'Certo')->count() / $q->qtd_respostas * 100 ;
                $q->porcentagem_medio = $niveis->where('nivel','Médio')->where('resultado', 'Certo')->count() / $q->qtd_respostas * 100 ;
                $q->porcentagem_dificil = $niveis->where('nivel','Dificil')->where('resultado', 'Certo')->count() / $q->qtd_respostas * 100 ;
//
            } else {
                $q->corretas = $q->incorretas = $q->porcentagem_corretas = $q->porcentagem_incorretas = 0;
            }
        }

        return $questoes;
    }
}
