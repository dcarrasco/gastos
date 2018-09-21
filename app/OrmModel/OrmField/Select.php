<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Field;
use Illuminate\Database\Eloquent\Model;

class Select extends Field
{
    protected $choices = [];

    /**
     * Fija opciones para tipo de campo Select
     * @param  array  $options
     * @return Field
     */
    public function options($options = [])
    {
        $this->choices = $options;

        return $this;
    }

    /**
     * Devuelve valor del campo formateado
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getFormattedValue(Request $request, Model $model = null)
    {
        $value = $model->{$this->getField()};

        if ($this->hasChoices()) {
            return array_get($this->choices, $value, '');
        }

        return $value;
    }

    /**
     * Indica si el campo tiene opciones
     * @return boolean
     */
    public function hasChoices()
    {
        return count($this->choices) > 0;
    }

    /**
     * Devuelve elemento de formulario para el campo
     * @param  Request       $request
     * @param  Resource|null $resource
     * @param  array         $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource = null, $extraParam = [])
    {
        $extraParam['id'] = $this->field;
        $extraParam['class'] = $extraParam['class'] . ' custom-select';
        $value = $resource->getModelObject()->{$this->getField()};

        return Form::select($this->field, $this->choices, $value, $extraParam);
    }
}
