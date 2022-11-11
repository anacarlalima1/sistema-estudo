<?php

namespace App\Http\Controllers;

use App\Models\Questoes;
use App\Models\Resposta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestaoController extends Controller
{
    public function cadastrarQuestao(Request $request)
    {
        try {
            $validation = $this->validatorQuestao($request->all());
            if ($validation->fails()) {
                return $validation->errors();
            }
            $dados['descricao'] = $request['descricao'];
            $dados['ra'] = $request['ra'];
            $dados['rb'] = $request['rb'];
            $dados['rc'] = $request['rc'];
            $dados['rd'] = $request['rd'];
            $dados['re'] = $request['re'];
            $dados['rgabarito'] = $request['rgabarito'];
            $dados['comentario'] = $request['comentario'];
            $dados['nivel'] = $request['nivel'];

            Questoes::insert($dados);

            return response()->json(['success' => true, 'message' => 'Questão adicionada com sucesso!']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 400);
        }
    }
    public function inativarQuestao(Request $request)
    {
        try {
            $res = Resposta::where('id', $request['id'])->pluck('id_questao', $request['id_questao'])->first();

            if ($res) {
                return response()->json(['success' => false, 'message' => 'Erro ao tentar. A questão contém respostas.'], 500);
            }

            Questoes::where('id', $request['id'])
                ->update(['status' => 'Inativo',]);

            return response()->json(['success' => true, 'message' => 'A questão foi inativada com sucesso!']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'exception' => $exception->getMessage()], 400);
        }
            }

    protected function validatorQuestao(array $data)
    {
        return Validator::make($data, [
            'descricao' => ['required'],
            'ra' => ['required'],
            'rb' => ['required'],
            'rc' => ['required'],
            'rd' => ['required'],
            're' => ['required'],
            'rgabarito' => ['required'],
            'comentario' => ['required'],
        ], [
            'required' => 'Campo obrigatório',
        ]);
    }

}
