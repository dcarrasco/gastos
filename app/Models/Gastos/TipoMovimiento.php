<?php

namespace App\Models\Gastos;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoMovimiento extends Model
{
    use HasFactory;

    protected $table = 'cta_tipos_movimientos';

    protected $fillable = ['tipo_movimiento', 'signo', 'orden'];


    public function tiposGastos()
    {
        return $this->hasMany(TipoGasto::class);
    }

    public static function selectOptions(): Collection
    {
        return static::orderBy('orden')->get()
            ->pluck('tipo_movimiento', 'id');
    }
}
