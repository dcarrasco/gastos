<?php

namespace App\OrmModel\OrmField;

use Form;
use App\OrmModel\OrmField;

class OrmFieldBoolean extends OrmField
{
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
        return $value
            ? "<small><span class=\"fa fa-circle text-success\"></span></small>&nbsp;&nbsp;" . trans('orm.radio_yes')
            : "<small><span class=\"fa fa-circle text-danger\"></span></small>&nbsp;&nbsp;" . trans('orm.radio_no');
    }


    public function getForm($value = null, $parentId = null, $extraParam = [])
    {
        $extraParam['id'] = $this->name;

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
