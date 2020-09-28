<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;
use Illuminate\Database\Eloquent\Model;

class Date extends Field
{
    public $inputDateFormat = 'Y-m-d';
    public $outputDateFormat = 'Y-m-d';

    /**
     * Devuelve valor del campo formateado
     *
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getFormattedValue(Model $model, Request $request)
    {
        return optional($model->{$this->attribute})->format($this->outputDateFormat);
    }

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
        $extraParam['class'] = ($extraParam['class'] ?? '') . $this->defaultClass;
        $value = $resource->model()->{$this->attribute};

        return Form::date($this->attribute, $value, $extraParam);
    }
}
