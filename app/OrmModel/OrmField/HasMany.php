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
        $relatedResource = $this->getRelation($model);

        if ($relatedResource->getModelList()->count() === 0)
        {
            return '';
        }

        $list = "<ul><li>"
            .$relatedResource->getModelList()
                ->map(function($model) use ($relatedResource, $request) {
                    return $relatedResource->injectModel($model)->title($request);
                })
                ->implode('</li><li>')
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
        $extraParam['id'] = $this->getFieldName($resource);
        $extraParam['class'] = $extraParam['class'] . ' custom-select';

        $field = $this->getFieldName($resource);
        $value = $resource->model()->{$field};

        $elementosSelected = collect($value)->map(function ($resourceElem) {
            return $resourceElem->getKey();
        })
        ->all();

        return Form::select(
            $this->name.'[]',
            $this->getRelationOptions($request, $resource, $this->getFieldName(), $this->relationConditions),
            $elementosSelected,
            array_merge(['multiple' => 'multiple', 'size' => 7], $extraParam)
        );
    }

}
