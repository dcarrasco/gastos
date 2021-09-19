<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;
use Illuminate\Database\Eloquent\Model;

class Gravatar extends Field
{
    protected int $avatarListSize = 24;
    protected int $avatarShowSize = 240;

    /**
     * Constructor de la clase
     *
     * @param string $name  Nombre o label de la clase
     * @param string $field Campo
     */
    public function __construct($name = '', $field = null)
    {
        $field = $field ?? 'email';
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
     * @param  Model    $model
     * @param  Request  $request
     * @return string
     */
    public function getValue(Model $model, Request $request): string
    {
        $size = Str::contains($request->route()->action['as'], 'show')
            ? $this->avatarShowSize : $this->avatarListSize;

        return $this->getGravatarUrl($model->getAttribute($this->attribute), $size);
    }

    /**
     * Devuelve valor del campo formateado
     *
     * @return HtmlString
     */
    public function getFormattedValue(): HtmlString
    {
        return new HtmlString("<img src=\"{$this->value}\" class=\"rounded-full border\">");
    }
}
