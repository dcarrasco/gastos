<?php

namespace App\Inventario;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    protected $fillable = ['centro'];
    protected $primaryKey = 'centro';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_centros');
    }
}
