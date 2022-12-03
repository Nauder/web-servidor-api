<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use App\Models\veiculo;
use Illuminate\Support\Facades\Validator;

/**
 * Responsável pelo gerenciamento de Veículos
 */
class VeiculoController extends CrudController
{

    public function __construct()
    {
        parent::__construct(new veiculo());
    }

    protected function validarDados(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'placa'         => 'required|formato_placa_de_veiculo|unique:veiculo,placa',
                'modelo'        => 'required',
                'marca'         => 'required',
                'cor'           => 'required',
                'ano'           => 'required|digits:4|integer|min:1900|max:' . (date('Y')+1),
                'quilometragem' => 'required|integer',
                'custo_dia'     => 'required|integer',
                'id_empresa'    => 'required|integer|exists:empresa,id',
            ]
        );
    }

    protected function validarDadosUpdate(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'placa'         => 'formato_placa_de_veiculo|unique:veiculo,placa',
                'id_empresa'    => 'integer|exists:empresa,id',
                'ano'           => 'year',
                'quilometragem' => 'integer',
                'custo_dia'     => 'integer'
            ]
        );
    }

}
