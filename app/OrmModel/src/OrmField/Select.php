<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Field;
use Illuminate\Database\Eloquent\Model;

class Select extends Field
{
    protected $choices = [];

    /**
     * Fija opciones para tipo de campo Select
     *
     * @param  array  $options
     * @return Field
     */
    public function options(array $options = []): Field
    {
        $this->choices = $options;

        return $this;
    }

    /**
     * Devuelve valor del campo formateado
     *
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getValue(Model $model = null, Request $request)
    {
        $value = $model->{$this->attribute};

        if ($this->hasChoices()) {
            return Arr::get($this->choices, $value, '');
        }

        return $value;
    }

    /**
     * Indica si el campo tiene opciones
     *
     * @return boolean
     */
    public function hasChoices(): bool
    {
        return count($this->choices) > 0;
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
        $extraParam['class'] = Arr::get('class', $extraParam, '') . ' custom-select';
        $value = $resource->model()->{$this->attribute};

        return Form::select($this->attribute, $this->choices, $value, $extraParam);
    }
}
