<?php

namespace App\Models\Gastos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Gastos\Banco
 * @property int $id
 * @property string $nombre
 */
class Banco extends Model
{
    use HasFactory;

    protected $table = 'cta_bancos';

    protected $fillable = ['nombre'];
}
