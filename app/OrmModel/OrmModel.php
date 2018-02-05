<?php

namespace App\OrmModel;

use App\OrmModel\OrmField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OrmModel extends Model
{
    use Notifiable;

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
        parent::__construct($attributes);

        foreach ($this->modelFields as $field => $fieldSpec) {
            if (is_array($fieldSpec)) {
                $fieldSpec['name'] = $field;
                $fieldSpec['parentModel'] = get_class($this);
                $this->modelFields[$field] = new OrmField($fieldSpec);
            }
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

        $this->getModelFields()->filter(function ($elem) {
            return (isset($elem['tipo']) and $elem['tipo'] === OrmField::TIPO_CHAR);
        })->each(function ($item, $field) use (&$query, $filtro) {
            $query = $query->orWhere($field, 'like', '%'.$filtro.'%');
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
        $relatedModelClass = $this->getField($field)->getRelationModel();

        if (!empty($relatedModelClass)) {
            return new $relatedModelClass;
        }

        return;
    }

    public function getFieldLabel($field = '')
    {
        if (in_array($this->getFieldType($field), [OrmField::TIPO_HAS_ONE, OrmField::TIPO_HAS_MANY])) {
            return $this->getRelatedModel($field)->modelLabel;
        }

        return $this->getField($field)->getLabel();
    }

    public function getFieldsList($mostrarID = false)
    {
        return collect($this->modelFields)
            ->filter(function ($field) {
                return $field->getMostrarLista();
            })
            ->filter(function ($field) use ($mostrarID) {
                return ($mostrarID or $field->getTipo() !== OrmField::TIPO_ID);
            })
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

        $query = self::where($whereValue);

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
            })->all();
    }


    // ****************************************************************************
    // OVERRIDES PARA USAR MULTIPLE PRIMARY KEY
    // ****************************************************************************

    public function getKey()
    {
        if (is_array($this->getKeyName())) {
            $keyValues = [];
            foreach ($this->getKeyName() as $keyName) {
                $keyValues[] = $this->getAttribute($keyName);
            }

            return implode($this::KEY_SEPARATOR, $keyValues);
        }

        return $this->getAttribute($this->getKeyName());
    }
}
