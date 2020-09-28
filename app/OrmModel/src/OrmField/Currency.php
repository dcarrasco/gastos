<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;
use Illuminate\Database\Eloquent\Model;

class Currency extends Field
{
    protected $alignOnList = 'text-right';

    protected $currencySign = '$';


    /**
     * Devuelve valor del campo formateado
     *
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getFormattedValue(Model $model, Request $request)
    {
        return fmtMonto($model->{$this->attribute});
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

        return Form::number($this->attribute, $value, $extraParam);
    }
}
