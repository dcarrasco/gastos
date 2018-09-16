<?php

namespace App\OrmModel;

use DB;
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

    public $title = 'id';
    public $search = ['id'];

    public $modelLabel = '';
    protected $modelOrder = [];

    public $timestamps = true;
    protected $fillable = [];
    protected $perPage = 12;

    protected $sortByKey = 'sort-by';
    protected $sortDirectionKey = 'sort-direction';

    public function __construct(array $attributes = [])
    {
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

        $search = $this->search;
        collect($this->fields())
            ->filter(function ($field) use ($search) {
                return in_array($field->getField(), $search);
            })
            ->map(function ($field) {
                return $field->getField();
            })
            ->each(function ($field) use (&$query, $filtro) {
                $query = $query->orWhere($field, 'like', '%'.$filtro.'%');
            });

        return $query;
    }


    public function scopeModelOrderBy($query)
    {
        $orderBy = request($this->sortByKey, '');
        $orderDirection = request($this->sortDirectionKey, 'asc');

        if (!empty($orderBy)) {
            $this->modelOrder = [$orderBy => $orderDirection];
        }

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

    public function title()
    {
        return $this->{$this->title};
    }


    public function getField($field = '')
    {
        return array_get($this->modelFields, $field, new OrmField);
    }

    public function getTable()
    {
        return $this->table;
    }


    public function getRelatedModel($field = '')
    {
        return $this->getField($field)->getRelatedModel();
    }


    public function indexFields()
    {
        return collect($this->fields())
            ->filter(function($field) {
                return $field->showOnIndex();
            })
            ->all();
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

    public function getValidation()
    {
        $resource = $this;

        return collect($this->fields())
            ->mapWithKeys(function($field) use ($resource){
                return [$field->getField() => $field->getValidation($resource)];
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
