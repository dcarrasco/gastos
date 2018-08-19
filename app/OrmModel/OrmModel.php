<?php

namespace App\OrmModel;

use DB;
use App\OrmModel\OrmField;
use App\OrmModel\OrmField\OrmFieldInt;
use App\OrmModel\OrmField\OrmFieldChar;
use App\OrmModel\OrmField\OrmFieldHasOne;
use App\OrmModel\OrmField\OrmFieldHasMany;
use App\OrmModel\OrmField\OrmFieldBoolean;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OrmModel extends Model
{
    use Notifiable;
    use hasMultiKey;

    /**
     * Constantes de tipos de campos
     */
    const KEY_SEPARATOR = '~';

    public $tableColumns = [];
    protected $modelFields = [];
    protected $modelOrder = [];
    public $modelLabel = '';

    public $timestamps = false;
    protected $fillable = [];
    protected $perPage = 12;

    public function __construct(array $attributes = [])
    {
        $this->initFields();
        parent::__construct($attributes);
    }

    public function initFields($arrFields = [])
    {
        $fieldClasses = [
            OrmField::TIPO_ID => OrmFieldInt::class,
            OrmField::TIPO_INT => OrmFieldInt::class,
            OrmField::TIPO_CHAR => OrmFieldChar::class,
            OrmField::TIPO_BOOLEAN => OrmFieldBoolean::class,
            OrmField::TIPO_HAS_ONE => OrmFieldHasOne::class,
            OrmField::TIPO_HAS_MANY => OrmFieldHasMany::class,
        ];

        $this->modelFields = array_merge($this->modelFields, $arrFields);

        foreach ($this->modelFields as $field => $fieldSpec) {
            $fieldSpec = is_array($fieldSpec) ? $fieldSpec : ['tipo' => $fieldSpec];

            $fieldSpec['name'] = $field;
            $fieldSpec['parentModel'] = get_class($this);

            if ($field === $this->primaryKey) {
                $fieldSpec['esId'] = true;
                $fieldSpec['esIncrementing'] = $this->incrementing;
            }

            if (array_key_exists($fieldSpec['tipo'], $fieldClasses)) {
                $fieldClass = $fieldClasses[$fieldSpec['tipo']];
                $fieldObject = new $fieldClass($fieldSpec);
            } else {
                $fieldObject = new OrmField($fieldSpec);
            }

            $this->modelFields[$field] = $fieldObject;
        }
    }

    public static function new()
    {
        return new static;
    }

    public function scopeFiltroOrm($query, $filtro = null)
    {
        if (empty($filtro)) {
            return $query;
        }

        $this->getModelFields()
            ->filter(function ($field) {
                return ($field->getTipo() === OrmField::TIPO_CHAR);
            })
            ->keys()
            ->each(function ($fieldName) use (&$query, $filtro) {
                $query = $query->orWhere($fieldName, 'like', '%'.$filtro.'%');
            });

        return $query;
    }

    public function scopeModelOrderBy($query)
    {
        if (isset($this->modelOrder)) {
            if (!is_array($this->modelOrder)) {
                $this->modelOrder = [$this->modelOrder => 'asc'];
            }

            foreach ($this->modelOrder as $field => $order) {
                $query = $query->orderBy($field, $order);
            }
        }

        return $query;
    }

    public function getModelFields()
    {
        return collect($this->modelFields);
    }

    public function getField($field = '')
    {
        return array_get($this->modelFields, $field, new OrmField);
    }

    public function getRelatedModel($field = '')
    {
        return $this->getField($field)->getRelatedModel();
    }

    public function getFieldLabel($field = '')
    {
        return $this->getField($field)->getLabel();
    }

    public function getFieldSortingIcon($field = '')
    {
        $iconClass = 'fa fa-sort text-muted';

        if (array_key_exists($field, $this->modelOrder)) {
            $iconClass = 'fa fa-sort';
        }

        return "<span class=\"{$iconClass}\"><span>";
    }
    public function getFieldsList($mostrarID = false)
    {
        return collect($this->modelFields)
            ->filter(function ($field) {
                return $field->getMostrarLista();
            })
            // ->filter(function ($field) use ($mostrarID) {
            //     return ($mostrarID or $field->getTipo() !== OrmField::TIPO_ID);
            // })
            ->keys()
            ->all();
    }

    public function getFieldHelp($field = '')
    {
        return $this->getField($field)->getTextoAyuda();
    }

    public function getFieldType($field = '')
    {
        return $this->getField($field)->getTipo();
    }

    public function getFieldLength($field = '')
    {
        return $this->getField($field)->getLargo();
    }

    public function getFormattedFieldValue($field = '')
    {
        if (!array_key_exists($field, $this->modelFields)) {
            return null;
        }

        return $this->getField($field)->getFormattedValue($this->{$field});
    }

    public function getFieldForm($field = null, $extraParam = [])
    {
        return $this->getField($field)->getForm($this->getAttribute($field), $this->getKey(), $extraParam);
    }

    public function isFieldMandatory($field = '')
    {
        return $this->getField($field)->getEsObligatorio();
    }

    public function getValidation()
    {
        return $this->getModelFields()
            ->map(function ($fieldObject, $field) {
                return $fieldObject->getValidation();
            })
            ->all();
    }

    public static function getModelFormOptions($where = [])
    {
        $whereIn = collect($where)->filter(function ($elem, $key) {
            return !is_integer($key) and is_array($elem);
        });

        $whereValue = collect($where)->filter(function ($elem, $key) {
            return is_integer($key) or !is_array($elem);
        })->all();

        $query = static::where($whereValue);

        if (! $whereIn->isEmpty()) {
            $whereIn->each(function ($elem, $key) use (&$query) {
                return $query->whereIn($key, $elem);
            });
        }
        if (isset(static::$orderField)) {
            $query = $query->orderBy(static::$orderField);
        }

        return $query->get()->mapWithKeys(function ($model) {
            return [$model->getKey() => (string) $model];
        });
    }

    public static function getModelAjaxFormOptions($where = [])
    {
        return ajax_options(static::getModelFormOptions($where));
    }

    public function getWhereFromRelation($field = null)
    {
        if (!$this->getField($field)->hasRelationConditions()) {
            return [];
        }

        $object = $this;

        return collect($this->getField($field)->getRelationConditions())
            ->map(function ($elem, $key) use ($object) {
                list($tipo, $campo, $default) = explode(':', $elem);
                return $object->{$campo};
            })
            ->all();
    }
}
