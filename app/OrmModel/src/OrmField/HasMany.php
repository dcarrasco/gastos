<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Relation;
use Illuminate\Database\Eloquent\Model;

class HasMany extends Relation
{
    /**
     * Constructor de la clase
     *
     * @param string $name            Nombre o label de la clase
     * @param string $field           Campo
     * @param string $relatedResource Nombre del recurso relacionado
     */
    public function __construct(string $name = '', string $field = '', string $relatedOrm = '')
    {
        $this->showOnList = false;
        parent::__construct($name, $field, $relatedOrm);
    }

    /**
     * Devuelve valor del campo formateado
     *
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getFormattedValue(Model $model, Request $request)
    {
        $relatedResources = $model->{$this->attribute}->mapInto($this->relatedResource);

        $list = $relatedResources->count() === 0
            ? ''
            : "<ul><li>" . $relatedResources->map->title()->implode('</li><li>') . "</li></ul>";

        return new HtmlString($list);
    }

    /**
     * Devuelve elemento de formulario para el campo
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, $extraParam = []): HtmlString
    {
        $extraParam['id'] = $this->attribute;
        $extraParam['class'] = Arr::get($extraParam, 'class', '') . ' custom-select';

        return Form::select(
            "{$this->name}[]",
            $this->getRelationOptions($request, $resource, $this->relationConditions),
            $resource->model()->{$this->attribute}->modelKeys(),
            array_merge(['multiple' => 'multiple', 'size' => 7], $extraParam)
        );
    }
}
