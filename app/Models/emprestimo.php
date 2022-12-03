<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class emprestimo extends Model
{
    use HasFactory;

    protected $table = 'emprestimo';
    
    protected $fillable = [
        'data_emprestimo',
        'data_entrega',
        'id_veiculo',
        'id_usuario',
        'id_empresa_emprestimo',
        'id_empresa_entrega'
    ];

    public function veiculo() {
        return $this->hasOne('app/Models/veiculo');
    }

    public function usuario() {
        return $this->hasOne('app/Models/usuario');
    }

    public function empresa() {
        return $this->hasMany('app/Models/empresa');
    }

    /**
     * Retorna quantidade de empréstimos já cadastrados para dado veículo em dado período
     *
     * @param mixed $data_inicio
     * @param mixed $data_fim
     * @param int $veiculo
     * @return Collection
     */
    public function verificarDisponibiidade(mixed $data_inicio, mixed $data_fim, int $veiculo): Collection
    {
        $inicio = date('Y-m-y', strtotime(str_replace('/', '-', $data_inicio)));
        $fim = date('Y-m-y', strtotime(str_replace('/', '-', $data_fim)));
        return $this::where(DB::raw(
            "'{$inicio}'::DATE BETWEEN data_emprestimo AND data_entrega"
        ))->orWhere(DB::raw(
            "'{$fim}'::DATE BETWEEN data_emprestimo AND data_entrega"
        ))->Where(DB::raw(
            "id_veiculo = {$veiculo}"
        ))->get();
    }
}
