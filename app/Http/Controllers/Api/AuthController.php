<?php

namespace App\Http\Controllers\Api;

use App\Models\usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Responsável pela autenticação e CRUD de tokens API
 */
class AuthController extends Controller
{

    /**
     * Listagem de tokens do usuário logado
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function read(Request $request): JsonResponse
    {
        try {

            if (isset($request->id)) {
                $usuarios = usuario::where('id', $request->id)->first();
            } else {
                $usuarios = usuario::all();
            }

            return response()->json([
                'status' => true,
                'usuarios' => $usuarios
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Create para Usuario
     * 
     * @param Request $request
     * @return JsonResponse 
     */
    public function createUsuario(Request $request): JsonResponse
    {
        try {
            // Valida o usuário
            $ValidarUsuario = Validator::make(
                $request->all(),
                [
                    'nome'            => 'required',
                    'email'           => 'required|email|unique:usuario,email',
                    'senha'           => 'required',
                    'data_nascimento' => 'required',
                    'telefone'        => 'required',
                    'cpf'             => 'required|unique:usuario,cpf'
                ]
            );

            // Resposta de erro se a validação falhar
            if ($ValidarUsuario->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'erro de validação',
                    'errors'  => $ValidarUsuario->errors()
                ], 401);
            }

            // Criação do usuário
            DB::beginTransaction();

            $usuario = usuario::create([
                'nome'            => $request->nome,
                'email'           => $request->email,
                'senha'           => Hash::make($request->senha),
                'data_nascimento' => $request->data_nascimento,
                'cpf'             => $request->cpf,
                'telefone'        => $request->telefone
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Usuario criado com Sucesso',
                'token' => $usuario->createToken($request->email . '_API')->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Create para Usuario Admin
     * 
     * @param Request $request
     * @return JsonResponse 
     */
    public function createUsuarioAdmin(Request $request): JsonResponse
    {
        try {
            // Valida o usuário
            $ValidarUsuario = Validator::make(
                $request->all(),
                [
                    'nome'            => 'required',
                    'email'           => 'required|email|unique:usuario,email',
                    'senha'           => 'required',
                    'data_nascimento' => 'required',
                    'telefone'        => 'required',
                    'cpf'             => 'required|unique:usuario,cpf'
                ]
            );

            // Resposta de erro se a validação falhar
            if ($ValidarUsuario->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'erro de validação',
                    'errors'  => $ValidarUsuario->errors()
                ], 401);
            }

            // Criação do usuário
            DB::beginTransaction();

            $usuario = usuario::create([
                'nome'            => $request->nome,
                'email'           => $request->email,
                'senha'           => Hash::make($request->senha),
                'data_nascimento' => $request->data_nascimento,
                'cpf'             => $request->cpf,
                'telefone'        => $request->telefone
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Usuario criado com Sucesso',
                'token' => $usuario->createToken($request->email . '_API', ['admin'])->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Realiza o login do usuário
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function loginUsuario(Request $request): JsonResponse
    {
        try {
            $ValidarUsuario = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'senha' => 'required'
                ]
            );

            if ($ValidarUsuario->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'E-mail ou senha incorretos',
                    'errors' => $ValidarUsuario->errors()
                ], 401);
            }

            $usuario = usuario::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'Usuario logado com sucesso',
                'token' => $usuario->createToken($request->email . '_API')->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
