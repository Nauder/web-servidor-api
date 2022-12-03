<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class veiculo extends Model
{
    use HasFactory;

    protected $table = 'veiculo';

    protected $fillable = [
        'placa',
        'modelo',
        'marca',
        'cor',
        'ano',
        'quilometragem',
        'custo_dia',
        'id_empresa'
    ];

    public function empresa() {
        return $this->hasOne('app/Models/empresa');
    }

}
