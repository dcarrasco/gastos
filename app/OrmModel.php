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
        if (in_array($this->getFieldType($field), [self::TIPO_HAS_ONE, self::TIPO_HAS_MANY])) {
            $relatedModel = new $this->modelFields[$field]['relation_model'];

            return $relatedModel->modelLabel;
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
            $relatedModel = new $this->modelFields[$field]['relation_model'];

            return (string) $relatedModel->find($this->{$field});
        }

        if ($this->getFieldType($field) === self::TIPO_HAS_MANY) {
            return ($this->{$field})
                ? '<ul>'
                    .$this->{$field}->reduce(function ($list, $relatedObject) {
                        return $list.'<li>'.(string) $relatedObject.'</li>';
                    }, '')
                    . '</ul>'
                : null;
        }

        return $this->{$field};
    }

    public function getFieldForm($field = null, $extraParam = [])
    {
        $extraParam['id'] = $field;

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
            $relatedModel = new $this->modelFields[$field]['relation_model'];

            if (array_key_exists('onchange', $this->modelFields[$field])) {
                $route = \Route::currentRouteName();
                list($routeName, $routeAction) = explode('.', $route);

                $elemDest = $this->modelFields[$field]['onchange'];
                $url = route($routeName.'.ajaxOnChange', ['modelName' => $elemDest]);
                $extraParam['onchange'] = "$('#{$elemDest}').html('');$.get('{$url}?{$field}='+$('#{$field}').val(), function (data) { $('#{$elemDest}').html(data); });";
            }

            return Form::select($field, $relatedModel->getModelFormOptions(), $this->getAttribute($field), $extraParam);
        }

        if ($this->getFieldType($field) === self::TIPO_HAS_MANY) {
            $relatedModel = new $this->modelFields[$field]['relation_model'];

            $elementosSelected = collect($this->getAttribute($field))
                ->map(function ($modelElem) {
                    return $modelElem->{$modelElem->getKeyName()};
                })->all();

            return Form::select($field.'[]', $relatedModel->getModelFormOptions($this->getWhereFromRelation($field)), $elementosSelected, array_merge(['multiple' => 'multiple', 'size' => 7], $extraParam));
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

    public function getModelFormOptions($where = [])
    {
        $elementosForm = [];
        self::where($where)->get()->each(function ($elem) use (&$elementosForm) {
            $elementosForm[$elem->getKey()] = (string) $elem;
        });

        return $elementosForm;
    }

    public function getModelAjaxFormOptions($where = [])
    {
        return collect($this->getModelFormOptions($where))
            ->map(function ($elem, $key) {
                return ['key' => $key, 'value' => $elem];
            })->reduce(function ($carry, $elem) {
                return $carry.'<option value="'.$elem['key'].'">'.e($elem['value']).'</option>';
        }, '');
    }

    public function getWhereFromRelation($field = null)
    {
        if (!array_key_exists('relation_conditions', $this->modelFields[$field])) {
            return [];
        }

        $object = $this;

        if (!array_key_exists('relation_conditions', $this->modelFields[$field])) {
            return [];
        }

        return collect($this->modelFields[$field]['relation_conditions'])
            ->map(function ($elem, $key) use ($object) {
                list($tipo, $campo, $default) = explode(':', $elem);
                return $object->{$campo};
            })->all();
    }

}
