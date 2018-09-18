<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField\Field;

class Boolean extends Field
{
    public function getFormattedValue($model = null)
    {
        return $model->{$this->getField()}
            ? "<small><span class=\"fa fa-circle text-success\"></span></small>&nbsp;&nbsp;" . trans('orm.radio_yes')
            : "<small><span class=\"fa fa-circle text-danger\"></span></small>&nbsp;&nbsp;" . trans('orm.radio_no');
    }


    public function getForm($resource = null, $parentId = null, $extraParam = [])
    {
        $extraParam['id'] = $this->name;
        $value = $resource->{$this->getField()};

        return '<div class="custom-control custom-radio">'
            .Form::radio($this->name, 1, ($value == '1'), ['id' => 'id_'.$this->name.'_1', 'class' => 'custom-control-input'])
            .'<label class="custom-control-label" for="id_'.$this->name.'_1">'.trans('orm.radio_yes').'</label>'
            .'</div>'
            .'<div class="custom-control custom-radio">'
            .Form::radio($this->name, 0, ($value != '1'), ['id' => 'id_'.$this->name.'_0', 'class' => 'custom-control-input'])
            .'<label class="custom-control-label" for="id_'.$this->name.'_0">'.trans('orm.radio_no').'</label>'
            .'</div>';
    }
}
