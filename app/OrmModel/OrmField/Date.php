<?php

namespace App\OrmModel\OrmField;

use Form;
use Carbon\Carbon;
use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Field;
use Illuminate\Database\Eloquent\Model;

class Date extends Field
{
    public $inputDateFormat = 'Y-m-d';
    public $outputDateFormat = 'Y-m-d';
    /**
     * Devuelve valor del campo formateado
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getValue(Request $request, Model $model = null)
    {
        $value = $model->{$this->getField()};

        return isset($value) ? $value->format($this->outputDateFormat) : '';
    }

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

        return Form::date($this->field, $value, $extraParam);
    }

}
