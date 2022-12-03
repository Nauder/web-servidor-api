<?php

namespace App\Http\Controllers\Api;

use App\Models\usuario;
use App\Models\emprestimo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Validator;

/**
 * Responsável pelo gerenciamento de Usuários
 */
class UsuarioController extends CrudController
{

    public function __construct()
    {
        parent::__construct(new usuario());
    }

    /**
     * Retorna o usuário atual e seus Tokens
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function readPessoal(Request $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => [
                        'usuario' => $request->user(),
                        'tokens'  => $request->user()->tokens
                      ]
        ], 200);
    }

    /**
     * Retorna os empréstimos relacionados ao usuário atual
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function readEmprestimos(Request $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => [
                        'emprestimos' => emprestimo::where('id', $request->user()->id)->get()
                      ]
        ], 200);
    }

    /**
     * Remove o usuário atual
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deletePessoal(Request $request): JsonResponse
    {
        try {

            // Remoção da tupla
            DB::beginTransaction();

            $request->user()->tokens()->delete();
            $this->db::destroy($request->user()->id);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => class_basename($this->db) . ' removido com sucesso'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'erro de requisisao, por favor verifique os parametos informados',
                'r' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Atualiza o usuário atual
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editarPessoal(Request $request): JsonResponse
    {
        try {

            // Validação de dados
            $validar = Validator::make(
                $request->all(),
                [
                    'email'           => 'email|unique:usuario,email',
                    'cpf'             => 'unique:usuario,cpf'
                ]
            );

            // Resposta de erro se a validação falhar
            if ($validar->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'erro de validação',
                    'errors'  => $validar->errors()
                ], 401);
            }

            // Atualização da tupla
            DB::beginTransaction();

            $this->db::where('id', $request->user()->id)->update($request->all());

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => class_basename($this->db) . ' atual atualizado com sucesso'
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
                'nome'            => 'required',
                'email'           => 'required|email|unique:usuario,email',
                'senha'           => 'required',
                'data_nascimento' => 'required|date_format:d/m/Y',
                'telefone'        => 'required|celular',
                'cpf'             => 'required|cpf|unique:usuario,cpf'
            ]
        );
    }

    protected function validarDadosUpdate(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'id'              => 'required',
                'email'           => 'email|unique:usuario,email',
                'data_nascimento' => 'date_format:d/m/Y',
                'telefone'        => 'celular',
                'cpf'             => 'unique:usuario,cpf'
            ]
        );
    }

}
