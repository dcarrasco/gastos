<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;

class Textarea extends Field
{
    protected $defaultMaxLength = 500;

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
            'value' => $resource->model()->getAttribute($this->attribute),
            'id' => $this->attribute,
        ])->render());
    }

    /**
     * Devuelve largo del campo, de acuerdo a regla de validacion
     *
     * @return int
     */
    protected function getFieldLength(): int
    {
        $maxRule = collect($this->rules)->first(function ($rule) {
            return strpos($rule, 'max:') !== false;
        });

        return is_null($maxRule) ? $this->defaultMaxLength : (int) explode(':', $maxRule)[1];
    }
}
