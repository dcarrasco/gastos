<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Acl\Modulo
 * @property string $modulo
 * @property string $descripcion
 * @property string $llaveModulo
 * @property string $icono
 * @property string $url
 * @property int $orden
 * @property object $pivot
 */
class Modulo extends Model
{
    use HasFactory;

    protected $table = 'acl_modulo';

    protected $fillable = ['app_id', 'modulo', 'descripcion', 'llave_modulo', 'icono', 'url', 'orden'];


    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function app()
    {
        return $this->belongsTo(App::class);
    }

    public function getUrlAttribute($value): string
    {
        return route($value);
    }

}
