<?php

namespace App\Models\Acl;

use App\Models\Acl\Modulo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory;

    protected $fillable = ['app_id', 'rol', 'descripcion'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'acl_rol';
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }

    public function modulo()
    {
        return $this->belongsToMany(Modulo::class, 'acl_rol_modulo')
            ->withPivot('abilities')
            ->withTimestamps();
    }
}
