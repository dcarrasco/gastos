<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;

class Text extends Field
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
        $extraParam['id'] = $this->attribute;
        $extraParam['maxlength'] = $this->getFieldLength();
        // $extraParam['placeholder'] = $this->name;

        if ($resource->model()->getKeyName() === $this->attribute
            && !is_null($resource->model()->getKey())
        ) {
            $extraParam['readonly'] = 'readonly';
        }
        $value = $resource->model()->{$this->attribute};

        return Form::text($this->attribute, $value, $extraParam);
    }

    /**
     * Devuelve largo del campo, de acuerdo a regla de validacion
     *
     * @return string
     */
    protected function getFieldLength(): int
    {
        $maxRule = collect($this->rules)->first(function($rule) {
            return strpos($rule, 'max:') !== false;
        });

        return substr($maxRule, strpos($maxRule, ':') + 1, strlen($maxRule));
    }
}
