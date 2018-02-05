<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField;

class OrmFieldHasMany extends OrmField
{
    public function getLabel()
    {
        return $this->getRelatedModel()->modelLabel.'***';
    }

    public function getValidation()
    {
        $validation = [];

        if ($this->esObligatorio) {
            $validation[] = 'required';
        }

        return collect($validation)->implode('|');
    }

    public function getFormattedValue($value = null)
    {
        return $value ? $value->reduce(function ($list, $relatedObject) {
            return $list.'<li>'.(string) $relatedObject.'</li>';
        }, '<ul>').'</ul>' : null;
    }

    public function getForm($value = null, $parentId = null, $extraParam = [])
    {
        $extraParam['id'] = $this->name;

        $elementosSelected = collect($value)
            ->map(function ($modelElem) {
                return $modelElem->{$modelElem->getKeyName()};
            })->all();

        $relatedModelFilter = $this->getRelatedModel($this->parentModel)
            ->find($parentId)
            ->getWhereFromRelation($this->name);

        return Form::select(
            $this->name.'[]',
            $this->getRelatedModel()->getModelFormOptions($relatedModelFilter),
            $elementosSelected,
            array_merge(['multiple' => 'multiple', 'size' => 7], $extraParam)
        );
    }
}
