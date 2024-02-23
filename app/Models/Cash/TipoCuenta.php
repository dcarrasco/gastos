<?php

namespace App\Models\Cash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cash\Cuenta
 *
 * @property string $tipo
 * @property string $nombre
 */
class TipoCuenta extends Model
{
    use HasFactory;

    protected $table = "cash_tipo_cuentas";

    protected $primaryKey = "tipo_cuenta";
    protected $keyType = 'string';

    protected $fillable = ['tipo_cuenta', 'nombre', 'tipo',
        'nombre_cargo', 'signo_cargo', 'nombre_abono', 'signo_abono'
    ];

}
