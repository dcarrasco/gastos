<?php

namespace App\Models\Acl;

use App\Models\Acl\Modulo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'acl_rol';

    protected $fillable = ['app_id', 'rol', 'descripcion'];

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function app()
    {
        return $this->belongsTo(App::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany */
    public function modulo()
    {
        return $this->belongsToMany(Modulo::class, 'acl_rol_modulo')
            ->withPivot('abilities')
            ->withTimestamps();
    }
}
