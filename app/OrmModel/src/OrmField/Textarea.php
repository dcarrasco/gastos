<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;

class Textarea extends Field
{
    /**
     * Devuelve elemento de formulario para el campo
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        return new HtmlString(view('orm.form-input', [
            'type' => 'textarea',
            'name' => $this->attribute,
            'rows' => 5,
            'maxlength' => $this->getFieldLength(),
            'value' => $resource->model()->{$this->attribute},
            'id' => $this->attribute,
        ])->render());
    }

    /**
     * Devuelve largo del campo, de acuerdo a regla de validacion
     *
     * @return string
     */
    protected function getFieldLength(): int
    {
        $maxRule = collect($this->rules)
            ->first(function ($rule) {
                return strpos($rule, 'max:') !== false;
            });

        return substr($maxRule, strpos($maxRule, ':') + 1, strlen($maxRule));
    }
}
