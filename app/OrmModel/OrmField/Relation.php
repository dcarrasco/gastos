<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Field;

class Relation extends Field
{
    protected $relatedOrm = '';

    public function __construct($name = '', $field = '', $relatedOrm = '')
    {
        $field = empty($field) ? $name : $field;
        $this->relatedOrm = empty($relatedOrm) ? $field : $relatedOrm;

        parent::__construct($name, $field);
    }

    public static function make($name = '', $field = '', $relatedOrm = '')
    {
        return new static($name, $field, $relatedOrm);
    }

    public function relationConditions($relationConditions)
    {
        $this->relationConditions = $relationConditions;

        return $this;
    }

    public function getRelationOptions($resource = null, $field = '', $resourceFilter = null)
    {
        $filter = $this->getResourceFilter($resource, $resourceFilter);

        $relatedModelObject = (new $this->relatedOrm)->resourceOrderBy()->getModelObject();
        $relation = empty($filter)
            ? $relatedModelObject->get()
            : $relatedModelObject->where($filter)->get();

        $relatedOrm = $this->relatedOrm;
        $options = $relation->mapWithKeys(function($model) use ($relatedOrm) {
            $resource = (new $relatedOrm)->injectModel($model);
            return [$model->getKey() => $resource->title()];
        })->all();

        if (get_class($this) === 'App\OrmModel\OrmField\BelongsTo') {
        }

        return $options;
    }

    protected function getResourceFilter($resource, $resourceFilter)
    {
        return collect($resourceFilter)
            ->map(function($condition) use ($resource) {
                if (strpos($condition, '@field_value:') !== false) {
                    list($label, $field, $defaul) = explode(':', $condition);

                    return $resource->getModelObject()->{$field};
                }
            })->all();
        }
}
