<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CrudModel extends Model
{
    use HasFactory;

    /**
     * Função de leitura genérica
     *
     * @param Request $request
     * @return Collection
     */
    static function genericRead(Request $request, Model $model): Collection
    {
        $query = $model::query();

        // Aplica os filtros se foram requisitados
        if (isset($request->query)) {
            foreach ($request->all() as $filtro => $valores) {

                // Explode para multiplos valores em caso de OR
                foreach (explode('||', $valores) as $key => $valor) {
                    $query->orWhere($filtro, '=', $valor);
                }
            }
        }

        return $query->get();
    }
}
