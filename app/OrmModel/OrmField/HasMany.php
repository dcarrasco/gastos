<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField\Relation;

class HasMany extends Relation
{
    public function __construct($name = '', $field = '')
    {
        $this->showOnList = false;
        parent::__construct($name, $field);
    }

    public function getFormattedValue($value = null)
    {
        if ($this->hasChoices()) {
            return array_get($this->getChoices(), $value, '');
        }

        return $value;
    }


    public function getForm($resource = null, $extraParam = [], $resourceFilter = null)
    {
        $extraParam['id'] = $this->name;
        $extraParam['class'] = $extraParam['class'] . ' custom-select';
        $value = $resource->{$this->getField()};

        $elementosSelected = collect($value)
            ->map(function ($resourceElem) {
                return $resourceElem->getKey();
            })
            ->all();

        return Form::select(
            $this->name.'[]',
            $this->getRelationResourceOptions($resource, $this->getField(), $this->relationConditions),
            $elementosSelected,
            array_merge(['multiple' => 'multiple', 'size' => 7], $extraParam)
        );
    }

}
