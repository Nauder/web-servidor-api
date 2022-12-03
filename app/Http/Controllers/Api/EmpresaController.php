<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use App\Models\empresa;
use Illuminate\Support\Facades\Validator;

/**
 * Responsável pelo gerenciamento de Empresas
 */
class EmpresaController extends CrudController
{

    public function __construct()
    {
        parent::__construct(new empresa());
    }

    protected function validarDados(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'cnpj'         => 'required|cnpj|unique:empresa,cnpj',
                'razao_social' => 'required',
                'endereco'     => 'required',
                'uf'           => 'required|uf',
            ]
        );
    }

    protected function validarDadosUpdate(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'id'   => 'required',
                'cnpj' => 'cnpj|unique:empresa,cnpj',
                'uf'   => 'uf'
            ]
        );
    }

}
