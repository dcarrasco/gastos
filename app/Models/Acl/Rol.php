<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory;

    protected $fillable = ['app_id', 'rol', 'descripcion'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_rol');
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }

    public function modulo()
    {
        return $this->belongsToMany(Modulo::class, config('invfija.bd_rol_modulo'))
            ->withPivot('abilities')
            ->withTimestamps();
    }

    public function getModuloAbilities(int $idModulo)
    {
        return json_decode($this->modulo
            ->where('id', $idModulo)
            ->first()
            ->pivot->abilities) ?? [];
    }
}
