<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Acl\Modulo
 * @property int $id
 * @property int $app_id
 * @property string $modulo
 * @property string $descripcion
 * @property string $llaveModulo
 * @property string $icono
 * @property string $url
 * @property int $orden
 * @property Illuminate\Database\Eloquent\Relations\Pivot $pivot
 * @property boolean $selected
 */
class Modulo extends Model
{
    use HasFactory;

    protected $table = 'acl_modulo';

    protected $fillable = ['app_id', 'modulo', 'descripcion', 'llave_modulo', 'icono', 'url', 'orden'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<App, Modulo>
     */
    public function app()
    {
        return $this->belongsTo(App::class);
    }
}
