<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Field;

class Text extends Field
{
    /**
     * Devuelve elemento de formulario para el campo
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, $extraParam = [])
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
     * @return string
     */
    protected function getFieldLength()
    {
        $maxRule = collect($this->rules)->first(function($rule) {
            return strpos($rule, 'max:') !== false;
        });

        return substr($maxRule, strpos($maxRule, ':') + 1, strlen($maxRule));
    }
}
