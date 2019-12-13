<?php

namespace App\OrmModel\src\OrmField;

use Form;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Relation;
use Illuminate\Database\Eloquent\Model;

class BelongsTo extends Relation
{
    /**
     * Devuelve valor del campo formateado
     *
     * @param  Model|null $model
     * @param  Request    $request
     * @return mixed
     */
    public function getValue(Model $model = null, Request $request)
    {
        $relatedModel = $model->{$this->attribute};

        return (new $this->relatedResource($relatedModel))->title();
    }

    public function getModelAttribute(Resource $resource): string
    {
        return $resource->model()->{$this->attribute}()->getForeignKeyName();
    }

    /**
     * Devuelve elemento de formulario para el campo
     * @param  Request  $request
     * @param  Resource $resource
     * @param  array    $extraParam
     * @return HtmlString
     */
    public function getForm(Request $request, Resource $resource, array $extraParam = []): HtmlString
    {
        $foreignKeyName = $this->getModelAttribute($resource);
        $field = $this->attribute;
        $extraParam['id'] = $foreignKeyName;
        $extraParam['class'] = $extraParam['class'] . ' custom-select';

        if ($this->hasOnChange()) {
            $extraParam['onchange'] = $this->makeOnChange($foreignKeyName);
        }

        $value = $resource->model()->{$foreignKeyName};
        $form = Form::select($foreignKeyName, $this->getOptions($request, $resource), $value, $extraParam);

        return new HtmlString(str_replace('>'.trans('orm.choose_option'), 'disabled >'.trans('orm.choose_option'), $form));
    }

    protected function makeOnChange(string $field)
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
            $url = route($routeName.'.ajaxOnChange', ['modelName' => $resourceDest]);
            return "$('#{$elemDest}').html('');"
                ."$.get('{$url}?{$field}='+$('#{$field}').val(), "
                ."function (data) { $('#{$elemDest}').html(data); });";
    }

    /**
     * Recupera opciones desde modelo relacionado
     * @param  Request       $request
     * @param  Resource|null $resource
     * @return array
     */
    protected function getOptions(Request $request, Resource $resource = null): array
    {
        $relationName = (new $this->relatedResource)->getLabel();
        $optionIni = ['' => trans('orm.choose_option').$relationName];

        $options = $this->getRelationOptions($request, $resource, $this->attribute, $this->relationConditions);

        foreach($options as $key => $value) {
            $optionIni[$key] = $value;
        }

        return $optionIni;
    }

}
