<?php

namespace App\OrmModel\src\OrmField;

use App\OrmModel\src\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Textarea extends Field
{
    protected int $defaultMaxLength = 500;

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
            'type' => 'textarea',
            'name' => $this->attribute,
            'rows' => 5,
            'maxlength' => $this->getFieldLength(),
            'value' => $resource->model()->getAttribute($this->attribute),
            'id' => $this->attribute,
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
