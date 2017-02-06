<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Form;

class OrmModel extends Model
{
    use Notifiable;

    /**
     * Constantes de tipos de campos
     */
    const TIPO_ID       = 'ID';
    const TIPO_INT      = 'INT';
    const TIPO_REAL     = 'REAL';
    const TIPO_CHAR     = 'CHAR';
    const TIPO_BOOLEAN  = 'BOOLEAN';
    const TIPO_DATETIME = 'DATETIME';
    const TIPO_HAS_ONE  = 'HAS_ONE';
    const TIPO_HAS_MANY = 'HAS_MANY';

    public $tableColumns = [];
    protected $modelFields = [];
    public $modelLabel = '';

    public $timestamps = false;
    protected $fillable = [];
    protected $perPage = 12;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function scopeFiltroOrm($query, $filtro = null)
    {
        if (empty($filtro)) {
            return $query;
        }

        $this->getModelFields()->filter(function ($elem) {
            return (isset($elem['tipo']) and $elem['tipo'] === self::TIPO_CHAR);
        })->each(function ($item, $field) use (&$query, $filtro) {
            $query = $query->orWhere($field, 'like', '%'.$filtro.'%');
        });

        return $query;
    }

    public function getModelFields()
    {
        return collect($this->modelFields);
    }

    public function getFieldLabel($field = null)
    {
        if ($this->getFieldType($field) === self::TIPO_HAS_ONE) {
            $relationModelName = '\\App\\'.ucfirst($this->modelFields[$field]['relation_model']);
            $relationModel = new $relationModelName;

            return $relationModel->modelLabel;
        }

        return isset($this->modelFields[$field]['label']) ? $this->modelFields[$field]['label'] : $field;
    }

    public function getFieldsList($mostrarTodos = false)
    {
        return collect($this->modelFields)->filter(function ($elem) {
            return ! (isset($elem['mostrar_lista']) and $elem['mostrar_lista'] === false);
        })->keys()
        ->all();
    }

    public function getFieldHelp($field = null)
    {
        return isset($this->modelFields[$field]['texto_ayuda']) ? $this->modelFields[$field]['texto_ayuda'] : null;
    }

    public function getFieldType($field = null)
    {
        return isset($this->modelFields[$field]['tipo']) ? $this->modelFields[$field]['tipo'] : null;
    }

    public function getFieldLength($field = null)
    {
        return isset($this->modelFields[$field]['largo']) ? $this->modelFields[$field]['largo'] : null;
    }

    public function getFormattedFieldValue($field)
    {
        if ($this->getFieldType($field) === self::TIPO_BOOLEAN) {
            return $this->{$field} ? trans('orm.radio_yes'): trans('orm.radio_no');
        }

        if ($this->getFieldType($field) === self::TIPO_HAS_ONE) {
            $relation_field = $this->modelFields[$field]['relation_model'];
            return (string) $this->{$relation_field};
        }

        if ($this->getFieldType($field) === self::TIPO_HAS_MANY) {
            $relation_field = $this->modelFields[$field]['relation_model'];

            return '<ul>'
                .$this->{$relation_field}->reduce(function ($list, $relationObject) {
                    return $list.'<li>'.(string) $relationObject.'</li>';
                }, '')
                . '</ul>';
        }

        return $this->{$field};
    }

    public function getFieldForm($field = null, $extraParam = [])
    {
        if ($this->getFieldType($field) === self::TIPO_CHAR and $this->getFieldLength($field)) {
            $extraParam['maxlength'] = $this->getFieldLength($field);
        }

        if ($field === $this->getKeyName() and $this->incrementing) {
            return '<p class="form-control-static">'.$this->{$field}.'</p>'
                .Form::hidden($field, null, $extraParam);
        }

        if ($this->getFieldType($field) === self::TIPO_BOOLEAN) {
            return '<label class="radio-inline" for="">'
                .Form::radio($field, 1, ($this->getAttribute($field) == '1'), ['id' => ''])
                .trans('orm.radio_yes')
                .'</label>'
                .'<label class="radio-inline" for="">'
                .Form::radio($field, 0, ($this->getAttribute($field) != '1'), ['id' => ''])
                .trans('orm.radio_no')
                .'</label>';
        }

        if ($this->getFieldType($field) === self::TIPO_HAS_ONE) {
            $relationModelName = '\\App\\'.ucfirst($this->modelFields[$field]['relation_model']);
            $relationModel = new $relationModelName;

            return Form::select($field, $relationModel->getModelFormOptions(), $this->getAttribute($field), $extraParam);
        }

        if ($this->getFieldType($field) === self::TIPO_HAS_MANY) {
            $relationModelName = '\\App\\'.ucfirst($this->modelFields[$field]['relation_model']);
            $relationModel = new $relationModelName;

            $elementosSelected = collect($this->getAttribute($field))
                ->map(function ($modelElem) {
                    return $modelElem->{$modelElem->getKeyName()};
                })->all();

            return Form::select($field.'[]', $relationModel->getModelFormOptions(), $elementosSelected, array_merge(['multiple' => 'multiple', 'size' => 7], $extraParam));
        }

        return Form::text($field, $this->getAttribute($field), $extraParam);
    }

    public function isFieldMandatory($field = null)
    {
        return isset($this->modelFields[$field]['es_obligatorio']) ? $this->modelFields[$field]['es_obligatorio'] : false;
    }

    public function getValidation()
    {
        $validation = [];
        foreach ($this->modelFields as $field => $fieldParam) {
            $validation[$field] = '';

            if ($this->isFieldMandatory($field)) {
                $validation[$field][] = 'required';
            }

            if ($this->getFieldType($field) === self::TIPO_CHAR and $this->getFieldLength($field)) {
                $validation[$field][] = 'max:'.$this->getFieldLength($field);
            }

            if ($this->getFieldType($field) === self::TIPO_INT) {
                $validation[$field][] = 'integer';
            }
        }

        return collect($validation)
            ->map(function ($elem, $key) {
                return collect($elem)->implode('|');
            })
            ->all();
    }

    public function getModelFormOptions($filtro = null)
    {
        $elementosForm = [];
        self::filtroOrm($filtro)->get()->each(function ($elem) use (&$elementosForm) {
            $elementosForm[$elem->getKey()] = (string) $elem;
        });

        return $elementosForm;
    }

    public function getModelAjaxFormOptions($filtro = null)
    {
        return collect($this->getModelFormOptions($filtro))
            ->map(function ($elem, $key) {
                return ['key' => $key, 'value' => $elem];
            })->reduce(function ($carry, $elem) {
                return $carry.'<option value="'.$elem['key'].'">'.e($elem['value']).'</option>';
        }, '');
    }

}
