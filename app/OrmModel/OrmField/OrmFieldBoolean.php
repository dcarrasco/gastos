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
        return $value ? trans('orm.radio_yes'): trans('orm.radio_no');
    }


    public function getForm($value = null, $parentId = null, $extraParam = [])
    {
        $extraParam['id'] = $this->name;

        return '<label class="radio-inline" for="">'
            .Form::radio($this->name, 1, ($value == '1'), ['id' => ''])
            .trans('orm.radio_yes')
            .'</label>'
            .'<label class="radio-inline" for="">'
            .Form::radio($this->name, 0, ($value != '1'), ['id' => ''])
            .trans('orm.radio_no')
            .'</label>';
    }
}
