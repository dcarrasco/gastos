<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField;

class RelationField extends OrmField
{
    protected $relationConditions = [];

    public function __construct($name = '', $field = '')
    {
        $this->showOnList = false;
        parent::__construct($name, $field);
    }

    public function relationConditions($relationConditions)
    {
        $this->relationConditions = $relationConditions;

        return $this;
    }

    public function getRelationResourceOptions($resource = null, $field = '', $resourceFilter = null)
    {
        $relation = call_user_func([$resource, $field])->getRelated();
        $filter = $this->getResourceFilter($resource, $resourceFilter);

        $relation = empty($filter) ? $relation->modelOrderBy() : $relation->modelOrderBy()->where($filter);

        return $relation->get()
            ->mapWithKeys(function($resource) {
                return [$resource->getKey() => $resource->title()];
            })
            ->all();
    }

    protected function getResourceFilter($resource, $resourceFilter)
    {
        return collect($resourceFilter)->map(function($condition) use ($resource) {
            if (strpos($condition, '@field_value:') !== false) {
                list($label, $field, $defaul) = explode(':', $condition);

                return $resource->{$field};
            }
        })->all();
    }
}
