<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\Resource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use App\OrmModel\OrmField\Relation;
use Illuminate\Database\Eloquent\Model;

class BelongsTo extends Relation
{
    /**
     * Devuelve nombre del campo de la BD
     * @param  Resource|null $resource
     * @return string
     */
    public function getField(Resource $resource = null)
    {
        if (is_null($resource)) {
            return $this->field;
        }

        $relationName = $this->field;

        return $resource->getModelObject()
            ->{$relationName}()
            ->getForeignKey();
    }

    /**
     * Devuelve valor del campo formateado
     * @param  Request    $request
     * @param  Model|null $model
     * @return mixed
     */
    public function getFormattedValue(Request $request, Model $model = null)
    {
        $relatedModel = $model->{$this->getField()};
        $related = (new $this->relatedResource)->injectModel($relatedModel);

        return $related->title($request);
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
        $extraParam['id'] = $this->getField($resource);
        $extraParam['class'] = $extraParam['class'] . ' custom-select';

        $field = $this->getField($resource);
        $value = $resource->getModelObject()->{$field};
        $foreignKey = $resource->getModelObject()->{$this->getField()}()->getForeignKey();

        if ($this->hasOnChange()) {
            $route = \Route::currentRouteName();
            list($routeName, $routeAction) = explode('.', $route);

            $elemDest = $this->onChange;
            $url = route($routeName.'.ajaxOnChange', ['modelName' => $elemDest]);
            $extraParam['onchange'] = "$('#{$elemDest}').html('');"
                ."$.get('{$url}?{$this->field}='+$('#{$this->field}').val(), "
                ."function (data) { $('#{$elemDest}').html(data); });";
        }

        $form = Form::select($foreignKey, $this->getOptions($request, $resource), $value, $extraParam);

        return new HtmlString(str_replace('>'.trans('orm.choose_option'), 'disabled >'.trans('orm.choose_option'), $form));
    }

    /**
     * Recupera opciones desde modelo relacionado
     * @param  Request       $request
     * @param  Resource|null $resource
     * @return array
     */
    protected function getOptions(Request $request, Resource $resource = null)
    {
        $relationName = (new $this->relatedResource)->getLabel();
        $optionIni = ['' => trans('orm.choose_option').$relationName];

        $options = $this->getRelationOptions($request, $resource, $this->getField(), $this->relationConditions);

        foreach($options as $key => $value) {
            $optionIni[$key] = $value;
        }

        return $optionIni;
    }

}
