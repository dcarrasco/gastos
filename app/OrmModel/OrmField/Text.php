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
        $extraParam['id'] = $this->field;
        $extraParam['maxlength'] = $this->getFieldLength();
        // $extraParam['placeholder'] = $this->name;

        if ($resource->getModelObject()->getKeyName() === $this->getField()
            && !is_null($resource->getModelObject()->getKey())
        ) {
            $extraParam['readonly'] = 'readonly';
        }
        $value = $resource->getModelObject()->{$this->getField()};

        return Form::text($this->field, $value, $extraParam);
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
