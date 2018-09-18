<?php

namespace App\OrmModel;

use DB;
use App\OrmModel\OrmField;

class OrmModel
{
    protected $perPage = 10;
    public $title = 'id';
    public $search = ['id'];

    protected $model = '';
    protected $modelObject = null;
    protected $modelList = null;

    public $label = '';
    protected $order = [];
    protected $sortByKey = 'sort-by';
    protected $sortDirectionKey = 'sort-direction';

    public function __construct()
    {
        if ($this->model === '') {
            throw new \Exception('Modelo no definido en recurso OrmModel!');
        }

        $this->makeModelObject();
    }

    public static function new()
    {
        return new static;
    }

    public function injectModel($model = null)
    {
        $this->modelObject = is_null($model) ? new $this->model : $model;

        return $this;
    }

    public function getModelObject()
    {
        return $this->modelObject;
    }

    public function resourceFilter($filtro = null)
    {
        if (empty($filtro)) {
            return $this;
        }

        $search = $this->search;
        $modelObject = $this->modelObject;

        collect($this->fields())
            ->filter(function ($field) use ($search) {
                return in_array($field->getField(), $search);
            })
            ->map(function ($field) {
                return $field->getField();
            })
            ->each(function ($field) use (&$modelObject, $filtro) {
                $modelObject = $modelObject->orWhere($field, 'like', '%'.$filtro.'%');
            });

        $this->modelObject = $modelObject;

        return $this;
    }


    public function resourceOrderBy()
    {
        $orderBy = request($this->sortByKey, '');
        $orderDirection = request($this->sortDirectionKey, 'asc');

        if (!empty($orderBy)) {
            $this->order = [$orderBy => $orderDirection];
        }

        if (isset($this->order)) {
            if (!is_array($this->order)) {
                $this->order = [$this->order => 'asc'];
            }

            foreach ($this->order as $field => $order) {
                $this->modelObject = $this->modelObject->orderBy($field, $order);
            }
        }

        return $this;
    }

    public function getValue($field)
    {
        $fieldObject = collect($this->fields())->first(function($fieldObject) use ($field) {
            return $fieldObject->getField() === $field;
        });

        if (! is_null($fieldObject)) {
            return $fieldObject->getFormattedValue($this->getModelObject());
        }

        return null;
    }

    public function title()
    {
        return $this->getValue($this->title);
    }


    public function getField($field = '')
    {
        return array_get($this->modelFields, $field, new OrmField);
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getLabel()
    {
        if (empty($this->label))
        {
            $class = explode("\\", get_class($this));
            return array_pop($class);
        }

        return $this->label;
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

    public function detailFields()
    {
        return collect($this->fields())
            ->filter(function($field) {
                return $field->showOnDetail();
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
            ->mapWithKeys(function($field) use ($resource) {
                return [$field->getField($resource) => $field->getValidation($resource)];
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

    public function makeModelObject()
    {
        $this->modelObject = (new $this->model)
            ->setPerPage($this->perPage);

        return $this;
    }

    public function getPaginated()
    {
        return $this->modelObject->paginate();
    }

    public function getModelList($request)
    {
        $this->modelList = $this->makeModelObject()
            ->resourceOrderBy()
            ->resourceFilter($request->get('filtro'))
            ->getPaginated();

        return $this->modelList;
    }

    public function getPaginationLinks($request)
    {
        return $this->modelList
            ->appends($request->only('filtro', 'sort-by', 'sort-direction'))
            ->links();
    }

    public function getName()
    {
        $fullName = explode("\\", get_class($this));
        return array_pop($fullName);
    }
}
