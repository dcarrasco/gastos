<?php

namespace App\Gastos;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = 'cta_bancos';

    protected $fillable = ['nombre'];

}
