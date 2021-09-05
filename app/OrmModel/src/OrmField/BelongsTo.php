<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Relation;

class BelongsTo extends Relation
{
    /**
     * Indica si debe cargar activamente todos los registros de la relacion
     *
     * @var boolean
     */
    protected bool $eagerLoadsRelation = true;


    /**
     * Devuelve valor del campo formateado
     *
     * @return HtmlString
     */
    public function getFormattedValue(): HtmlString
    {
        $relatedModel = $this->value;

        return new HtmlString($this->makeRelatedResource($relatedModel)->title());
    }

    /**
     * Recupera nombre del atributo (foreign key)
     *
     * @param Resource $resource
     * @return string
     */
    public function getModelAttribute(Resource $resource): string
    {
        return $resource->model()
            ->{$this->attribute}()
            ->getForeignKeyName();
    }

    /**
     * Devuelve elemento de formulario para el campo
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array<string> $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        $foreignKeyName = $this->getModelAttribute($resource);

        return new HtmlString(view('orm.form-input', [
            'type' => 'select',
            'name' => $foreignKeyName,
            'value' => $resource->model()->getAttribute($foreignKeyName),
            'id' => $foreignKeyName,
            'options' => $this->getRelationOptions($request, $resource, $this->relationConditions),
            'placeholder' => '&mdash;',
            'onchange' => $this->hasOnChange() ? $this->makeOnChange($request, $foreignKeyName) : '',
        ])->render());
    }

    /**
     * Genera HTML para script onchange
     *
     * @param Request $request
     * @param string  $field
     * @return HtmlString
     */
    protected function makeOnChange(Request $request, string $field): HtmlString
    {
        list($routeName, $routeAction) = explode('.', $request->route()->getName());

        $resourceDest = ucfirst($this->onChange);
        $elemDest = strtolower($this->onChange);
        $url = route("{$routeName}.ajaxOnChange", ['modelName' => $resourceDest]);

        return new HtmlString("$('#{$elemDest}').html('');"
            . "$.get('{$url}?{$field}='+$('#{$field}').val(), function (data) { $('#{$elemDest}').html(data); });");
    }
}
