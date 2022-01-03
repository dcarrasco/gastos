<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Relation;

class BelongsTo extends Relation
{
    /**
     * Indica si debe cargar activamente todos los registros de la relacion
     *
     * @var bool
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
     * @param  string[] $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        $foreignKeyName = $this->getModelAttribute($resource);

        return $this->renderForm([
            'type' => 'select',
            'name' => $foreignKeyName,
            'value' => $resource->model()->getAttribute($foreignKeyName),
            'id' => $foreignKeyName,
            'options' => $this->getRelationOptions($request, $resource, $this->relationConditions),
            'placeholder' => '&mdash;',
            'onchange' => $this->hasOnChange() ? $this->makeOnChange($request, $foreignKeyName) : '',
        ], $extraParam);
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

        return new HtmlString(
            "document.getElementById('{$elemDest}').innerHTML = '';"
            ."fetch('{$url}?{$field}=' + document.getElementById('{$field}').value)"
            .".then(response => response.text())"
            .".then(data => document.getElementById('{$elemDest}').innerHTML = data)"
            .".catch(error => console.log(error));"
        );
    }
}
