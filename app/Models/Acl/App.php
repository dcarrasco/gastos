<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Acl\App
 * @property int $id
 * @property string $app
 * @property string $descripcion
 * @property string $url
 * @property string $icono
 * @property int $orden
 */
class App extends Model
{
    use HasFactory;

    protected $table = 'acl_app';

    protected $fillable = ['app', 'descripcion', 'orden', 'url', 'icono'];
}
