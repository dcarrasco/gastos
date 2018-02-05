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
        $fieldClasses = [
            OrmField::TIPO_ID => OrmFieldInt::class,
            OrmField::TIPO_INT => OrmFieldInt::class,
            OrmField::TIPO_CHAR => OrmFieldChar::class,
            OrmField::TIPO_BOOLEAN => OrmFieldBoolean::class,
            OrmField::TIPO_HAS_ONE => OrmFieldHasOne::class,
            OrmField::TIPO_HAS_MANY => OrmFieldHasMany::class,
        ];


        foreach ($this->modelFields as $field => $fieldSpec) {
            if (is_array($fieldSpec)) {
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

        parent::__construct($attributes);
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


    // ****************************************************************************
    // OVERRIDES PARA USAR MULTIPLE PRIMARY KEY
    // ****************************************************************************

    public function hasMultiKey()
    {
        return $this->getModelFields()
            ->filter(function ($field) {
                return $field->getEsId();
            })
            ->count() > 1;
    }

    public function getKeyFields()
    {
        return $this->getModelFields()
            ->filter(function ($field) {
                return $field->getEsId();
            })
            ->keys();
    }

    public function getCompositeKeyFields()
    {
        return "CONCAT_WS('".static::KEY_SEPARATOR."', ".$this->getKeyFields()->implode(',').')';
    }

    public function getKey()
    {
        if ($this->hasMultiKey())
        {
            return $this->getKeyFields()
                ->map(function ($key) {
                    return $this->getAttribute($key);
                })
                ->implode($this::KEY_SEPARATOR);
        }

        return $this->getAttribute($this->getKeyName());
    }

    public static function findMultiKey($modelID)
    {
        $objectModel = new static;

        if ($objectModel->hasMultiKey())
        {
            $where = $objectModel->getKeyFields()
                ->combine(explode(static::KEY_SEPARATOR, $modelID))
                ->all();

            return new static((array) DB::table($objectModel->table)->where($where)->first());
        }

        return static::find($modelID);
    }

    public function updateMultiKey($arrValues)
    {
        $values = collect($arrValues);

        if ($this->hasMultiKey())
        {
            $keys = $this->getModelFields()
                ->filter(function ($field) {
                    return $field->getEsId();
                })
                ->map(function ($field, $key) use ($values) {
                    return $values->get($key);
                });

            $update = $values->forget($keys->keys()->all())
                ->only($this->getModelFields()->keys()->all());

            return DB::table($this->table)->where($keys->all())->update($update->all());
        }

        return $this->update($arrValues);
    }

    public function belongsToManyMultiKey($related, $table = null, $foreignPivotKey = null, $relatedPivotKey = null)
    {
        if (!is_array($foreignPivotKey) && !is_array($relatedPivotKey)) {
            return belongsToMany($related, $table, $foreignPivotKey, $relatedPivotKey);
        }

        $foreignPivotValues = $this->getKeyFields()
            ->map(function ($field) {
                return $this->getAttribute($field);
            });

        $foreignWhere = collect($foreignPivotKey)->combine($foreignPivotValues)->all();
        $pivotRecords = DB::table($table)->where($foreignWhere)->get();
        $relatedWhere = $pivotRecords->map(function ($pivotRow) use ($relatedPivotKey) {
            return collect($pivotRow)
                ->only(collect($relatedPivotKey))
                ->implode($this::KEY_SEPARATOR);
        });
        $relatedObject = new $related;

        return $relatedObject->whereIn(DB::raw($relatedObject->getCompositeKeyFields()), $relatedWhere->all())->get();
    }
}
