<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modulo extends Model
{
    use HasFactory;

    protected $fillable = ['app_id', 'modulo', 'descripcion', 'llave_modulo', 'icono', 'url', 'orden'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'acl_modulo';
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }
}
