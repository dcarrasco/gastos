<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;
use Illuminate\Database\Eloquent\Model;

class Gravatar extends Field
{
    protected $avatarListSize = 24;
    protected $avatarShowSize = 240;

    /**
     * Constructor de la clase
     *
     * @param string $name  Nombre o label de la clase
     * @param string $field Campo
     */
    public function __construct($name = '', $field = '')
    {
        $field = $field ?: 'email';
        parent::__construct($name, $field);

        $this->name = 'Avatar';
        $this->showOnForm = false;
    }

    /**
     * Devuelve el url del gravatar
     *
     * @param string $email
     * @param integer $size
     * @return string
     */
    protected function getGravatarUrl(string $email = '', int $size = 256): string
    {
        $md5Email = md5($email);

        return "https://secure.gravatar.com/avatar/{$md5Email}?size={$size}";
    }

    /**
     * Devuelve valor del campo formateado
     *
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getFormattedValue(Model $model, Request $request): HtmlString
    {
        $size = Str::contains($request->route()->action['as'], 'show')
            ? $this->avatarShowSize : $this->avatarListSize;

        $url = $this->getGravatarUrl($model->getAttribute($this->attribute), $size);

        return new HtmlString("<img src=\"{$url}\" class=\"rounded-full border\">");
    }
}
