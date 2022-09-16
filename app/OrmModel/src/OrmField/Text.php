<?php

namespace App\OrmModel\src\OrmField;

use App\OrmModel\src\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Text extends Field
{
    protected int $defaultMaxLength = 250;

    /**
     * Devuelve elemento de formulario para el campo
     *
     * @param  Request  $request
     * @param  resource  $resource
     * @param  string[]  $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        return $this->renderForm([
            'type' => 'text',
            'name' => $this->attribute,
            'value' => $resource->model()->getAttribute($this->attribute),
            'id' => $this->attribute,
            'maxlength' => $this->getFieldLength(),
            'placeholder' => $this->placeholder,
        ], $extraParam);
    }

    /**
     * Devuelve largo del campo, de acuerdo a regla de validacion
     *
     * @return int
     */
    protected function getFieldLength(): int
    {
        $maxRule = collect($this->rules)
            ->first(fn ($rule) => strpos($rule, 'max:') !== false);

        return is_null($maxRule) ? $this->defaultMaxLength : (int) Str::after($maxRule, ':');
    }
}
