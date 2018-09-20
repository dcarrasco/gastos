<?php

namespace App\OrmModel\OrmField;

use Form;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use App\OrmModel\OrmField\Relation;

class HasMany extends Relation
{
    public function __construct($name = '', $field = '', $relatedOrm = '')
    {
        $this->showOnList = false;
        parent::__construct($name, $field, $relatedOrm);
    }

    public function getFormattedValue(Request $request, $model = null)
    {
        $relatedResource = $this->getRelation($model);

        if ($relatedResource->getModelList()->count() === 0)
        {
            return '';
        }

        $list = "<ul><li>" . $relatedResource->getModelList()
                    ->map(function($model) use ($relatedResource, $request) {
                        return $relatedResource->injectModel($model)->title($request);
                    })
                    ->implode('</li><li>')
                ."</li></ul>";

        return new HtmlString($list);
    }

    public function getForm(Request $request, $resource = null, $extraParam = [], $resourceFilter = null)
    {
        $extraParam['id'] = $this->getField($resource);
        $extraParam['class'] = $extraParam['class'] . ' custom-select';

        $field = $this->getField($resource);
        $value = $resource->getModelObject()->{$field};

        $elementosSelected = collect($value)
            ->map(function ($resourceElem) {
                return $resourceElem->getKey();
            })
            ->all();

        return Form::select(
            $this->name.'[]',
            $this->getRelationOptions($request, $resource, $this->getField(), $this->relationConditions),
            $elementosSelected,
            array_merge(['multiple' => 'multiple', 'size' => 7], $extraParam)
        );
    }

}
