<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CrudController;
use App\Models\emprestimo;
use Illuminate\Support\Facades\Validator;

/**
 * Responsável pelo gerenciamento de Empréstimos
 */
class EmprestimoController extends CrudController
{

    public function __construct()
    {
        parent::__construct(new emprestimo());
    }

/**
     * Empréstimo de veículo
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function emprestarVeiculo(Request $request): JsonResponse
    {
        try {

            $request['id_usuario'] = $request->user()->id;

            // Validação de dados
            $validar = $this->validarDados($request);

            // Resposta de erro se a validação falhar
            if ($validar->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'erro de validação',
                    'errors'  => $validar->errors()
                ], 401);
            }

            // Criação da tupla
            DB::beginTransaction();

            // Verificação da disponibilidade do veículo
            $intersecoes = (new emprestimo())->verificarDisponibiidade(
                $request->data_emprestimo, 
                $request->data_entrega, 
                $request->id_veiculo
            );

            if($intersecoes->count() > 0) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Veículo requisitado indisponível no período solicitado'
                ], 500);
            }

            emprestimo::create($request->all());

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => class_basename(emprestimo::class) . ' criado com sucesso'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'erro de requisisao, por favor verifique os parametos informados',
                'r' => $th->getMessage()
            ], 500);
        }
    }

    protected function validarDados(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'data_emprestimo'       => 'required|date_format:d/m/Y|before:data_entrega',
                'data_entrega'          => 'required|date_format:d/m/Y|before_or_equal:' . date('d/m/Y'),
                'id_veiculo'            => 'required|integer|exists:veiculo,id',
                'id_usuario'            => 'required|integer|exists:usuario,id',
                'id_empresa_entrega'    => 'required|integer|exists:empresa,id',
                'id_empresa_emprestimo' => 'required|integer|exists:empresa,id',
            ]
        );
    }

    protected function validarDadosUpdate(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'data_emprestimo'       => 'date|before:data_entrega',
                'data_entrega'          => 'date|after:data_emprestimo',
                'id_veiculo'            => 'integer|exists:veiculo,id',
                'id_usuario'            => 'integer|exists:usuario,id',
                'id_empresa_entrega'    => 'integer|exists:empresa,id',
                'id_empresa_emprestimo' => 'integer|exists:empresa,id',
            ]
        );
    }

}
