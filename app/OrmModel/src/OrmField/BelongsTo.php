<?php

namespace App\OrmModel\src\OrmField;

use App\OrmModel\src\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

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
     * @param  resource  $resource
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
     * @param  resource  $resource
     * @param  string[]  $extraParam
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
            'options' => is_null($this->formOptions) ? $this->getRelationOptions($request, $resource, $this->relationConditions) : ($this->formOptions)(),
            'placeholder' => '&mdash;',
            'onchange' => $this->hasOnChange() ? $this->makeOnChange($request, $foreignKeyName) : '',
        ], $extraParam);
    }

    /**
     * Genera HTML para script onchange
     *
     * @param  Request  $request
     * @param  string  $field
     * @return HtmlString
     */
    protected function makeOnChange(Request $request, string $field): HtmlString
    {
        [$routeName, $routeAction] = explode('.', $request->route()->getName());

        $resourceDest = ucfirst($this->onChange);
        $elemDest = strtolower($this->onChange);
        $url = route("{$routeName}.ajaxOnChange", ['modelName' => $resourceDest]);

        return new HtmlString(
            "document.getElementById('{$elemDest}').innerHTML = '';"
            ."fetch('{$url}?{$field}=' + document.getElementById('{$field}').value)"
            .'.then(response => response.text())'
            .".then(data => document.getElementById('{$elemDest}').innerHTML = data)"
            .'.catch(error => console.log(error));'
        );
    }
}
