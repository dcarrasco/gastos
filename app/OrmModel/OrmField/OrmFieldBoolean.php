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
            ? "<small><span class=\"fa fa-circle text-success\"></span></small> " . trans('orm.radio_yes')
            : "<small><span class=\"fa fa-circle text-danger\"></span></small> " . trans('orm.radio_no');
    }


    public function getForm($value = null, $parentId = null, $extraParam = [])
    {
        $extraParam['id'] = $this->name;

        return '<div class="form-check" for="">'
            .Form::radio($this->name, 1, ($value == '1'), ['id' => '', 'class' => 'form-check-input'])
            .'<label class="form-check-label">'.trans('orm.radio_yes').'</label>'
            .'</div>'
            .'<div class="form-check" for="">'
            .Form::radio($this->name, 0, ($value != '1'), ['id' => '', 'class' => 'form-check-input'])
            .'<label class="form-check-label">'.trans('orm.radio_no').'</label>'
            .'</div>';
    }
}
