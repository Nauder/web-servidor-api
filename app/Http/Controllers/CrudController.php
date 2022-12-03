<?php

namespace App\Http\Controllers;

use App\Models\CrudModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

/**
 * Classe abstrata para CRUD genérico
 */
abstract class CrudController extends BaseController
{

    private Model $db;

    public function __construct(Model $db)
    {
        $this->db = $db;
    }

    /**
     * Criação genérica
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {

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

            $this->db::create($request->all());

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => class_basename($this->db) . ' criado com sucesso'
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
     * Listagem genérica com filtragem opcional
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function read(Request $request): JsonResponse
    {
        try {

            $dados = CrudModel::genericRead($request, $this->db);

            return response()->json([
                'status' => true,
                'data'   => [class_basename($this->db) . 's' => $dados]
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
     * Atualização genérica a partir de id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {

            // Validação de dados
            $validar = $this->validarDadosUpdate($request);

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

            $this->db::where('id', $request->id)->update($request->all());

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => class_basename($this->db) . ' atualizado com sucesso'
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
     * Remoção genérica a partir de id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        try {

            // Validação de dados
            if(isset($request->id)) {

                // Remoção da tupla
                DB::beginTransaction();

                $this->db::destroy($request->id);

                DB::commit();
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'o campo id e obrigatorio'
                ], 401);
            }

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

    // Função para validação de dados informados
    abstract protected function validarDados(Request $request);

    // Função para validação de dados para atualização
    abstract protected function validarDadosUpdate(Request $request);

}
