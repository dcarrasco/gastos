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
    public function getFormattedValue(): HtmlString
    {
        $relatedModel = $this->value;

        return new HtmlString(
            (new $this->relatedResource($relatedModel))->title()
        );
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
     * @param  array    $extraParam
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
     * @param string $field
     * @return HtmlString
     */
    protected function makeOnChange(Request $request, string $field): HtmlString
    {
        list($routeName, $routeAction) = explode('.', $request->route()->getName());

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
