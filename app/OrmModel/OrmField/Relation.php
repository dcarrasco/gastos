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

    public function getRelationResourceOptions($resource = null, $field = '', $resourceFilter = null)
    {
        $relation = call_user_func([$resource, $field])->getRelated();
        $filter = $this->getResourceFilter($resource, $resourceFilter);
        $optionIni = ['' => trans('orm.choose_option').$relation->getLabel()];
        $relation = empty($filter) ? $relation->modelOrderBy() : $relation->modelOrderBy()->where($filter);

        $options = $relation->get()->mapWithKeys(function($resource) {
            return [$resource->getKey() => $resource->title()];
        })->all();

        if (get_class($this) === 'App\OrmModel\OrmField\BelongsTo') {
            foreach($options as $key => $value) {
                $optionIni[$key] = $value;
            }

            $options = $optionIni;
        }

        return $options;
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
