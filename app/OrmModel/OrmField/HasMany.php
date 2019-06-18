<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use App\OrmModel\OrmField\Relation;
use Illuminate\Database\Eloquent\Model;

class HasMany extends Relation
{
    /**
     * Constructor de la clase
     * @param string $name            Nombre o label de la clase
     * @param string $field           Campo
     * @param string $relatedResource Nombre del recurso relacionado
     */
    public function __construct($name = '', $field = '', $relatedOrm = '')
    {
        $this->showOnList = false;
        parent::__construct($name, $field, $relatedOrm);
    }

    /**
     * Devuelve valor del campo formateado
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getValue(Model $model = null)
    {
        $relatedResources = $this->getRelation($model);

        if ($relatedResources->count() === 0) {
            return '';
        }

        $list = "<ul><li>"
            .$relatedResources->map->title()->implode('</li><li>')
            ."</li></ul>";

        return new HtmlString($list);
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
        $extraParam['id'] = $this->attribute;
        $extraParam['class'] = $extraParam['class'] . ' custom-select';

        $value = $resource->model()->{$this->attribute};

        $elementosSelected = collect($value)->map->getKey()->all();

        return Form::select(
            $this->name.'[]',
            $this->getRelationOptions($request, $resource, $this->attribute, $this->relationConditions),
            $elementosSelected,
            array_merge(['multiple' => 'multiple', 'size' => 7], $extraParam)
        );
    }

}
