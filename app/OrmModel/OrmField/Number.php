<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Field;

class Number extends Field
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
        $value = $resource->model()->{$this->getField()};

        return Form::number($this->field, $value, $extraParam);
    }

}