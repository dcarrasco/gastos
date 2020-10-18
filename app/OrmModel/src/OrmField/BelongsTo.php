<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Relation;
use Illuminate\Database\Eloquent\Model;

class BelongsTo extends Relation
{
    protected $eagerLoadsRelation = true;

    /**
     * Devuelve valor del campo formateado
     *
     * @param  Model   $model
     * @param  Request $request
     * @return mixed
     */
    public function getFormattedValue(Model $model, Request $request)
    {
        $relatedModel = $model->{$this->attribute};

        return (new $this->relatedResource($relatedModel))->title();
    }

    /**
     * Recupera nombre del atributo (foreign key)
     *
     * @param Resource $resource
     * @return string
     */
    public function getModelAttribute(Resource $resource): string
    {
        return $resource->model()->{$this->attribute}()->getForeignKeyName();
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
        $foreignKeyName = $this->getModelAttribute($resource);

        if ($this->hasOnChange()) {
            $extraParam['onchange'] = $this->makeOnChange($foreignKeyName);
        }

        return new HtmlString(view('orm.form-input', [
            'type' => 'select',
            'name' => $foreignKeyName,
            'value' => $resource->model()->{$foreignKeyName},
            'id' => $foreignKeyName,
            'options' => $this->getRelationOptions($request, $resource, $this->relationConditions),
            'placeholder' => '&mdash;'
        ])->render());
    }

    /**
     * Genera HTML para script onchange
     *
     * @param string $field
     * @return HtmlString
     */
    protected function makeOnChange(string $field): HtmlString
    {
        $route = \Route::currentRouteName();
        list($routeName, $routeAction) = explode('.', $route);
        if (!is_array($this->onChange)) {
            $this->onChange = [
                'resource' => ucfirst($this->onChange),
                'elem' => strtolower($this->onChange),
            ];
        }

        $resourceDest = Arr::get($this->onChange, 'resource');
        $elemDest = Arr::get($this->onChange, 'elem');
        $url = route("{$routeName}.ajaxOnChange", ['modelName' => $resourceDest]);

        return new HtmlString("$('#{$elemDest}').html('');"
            . "$.get('{$url}?{$field}='+$('#{$field}').val(), function (data) { $('#{$elemDest}').html(data); });");
    }
}
