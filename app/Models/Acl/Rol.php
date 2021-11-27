<?php

namespace App\Models\Acl;

use App\Models\Acl\Modulo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Acl\Rol
 * @property int $id
 * @property int $app_id
 * @property string $rol
 * @property string $descripcion
 */
class Rol extends Model
{
    use HasFactory;

    protected $table = 'acl_rol';

    protected $fillable = ['app_id', 'rol', 'descripcion'];

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    public function modulo(): BelongsToMany
    {
        return $this->belongsToMany(Modulo::class, 'acl_rol_modulo')
            ->withPivot('abilities')
            ->withTimestamps();
    }
}
