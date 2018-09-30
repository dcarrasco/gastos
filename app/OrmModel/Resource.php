<?php

namespace App\OrmModel;

use DB;
use App\OrmModel\OrmField;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Resource
{
    use UsesFilters;

    public $model = '';
    public $label = '';
    public $labelPlural = '';
    public $icono = 'table';
    public $title = 'id';

    public $search = ['id'];
    public $orderBy = [];

    protected $modelObject = null;
    protected $modelList = null;

    protected $perPage = 25;
    protected $sortByKey = 'sort-by';
    protected $sortDirectionKey = 'sort-direction';
    protected $filterKey = 'filtro';

    public function __construct()
    {
        if ($this->model === '') {
            throw new \Exception('Modelo no definido en recurso OrmModel!');
        }

        $this->makeModelObject(request());
    }

    /**
     * Campos del recurso
     * @param  Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [];
    }

    /**
     * Recupera nombre del recurso
     * @return string
     */
    public function getName()
    {
        $fullName = explode("\\", get_class($this));
        return array_pop($fullName);
    }

    /**
     * Devuelve descripcion del recurso
     * @return string
     */
    public function getLabel()
    {
        if (empty($this->label)) {
            $class = explode("\\", get_class($this));

            return array_pop($class);
        }

        return $this->label;
    }

    public function getLabelPlural()
    {
        if (empty($this->labelPlural)) {
            return Str::plural($this->getLabel());
        }

        return $this->labelPlural;
    }

    /**
     * Devuelve la representación del recurso
     * @param  Request $request
     * @return mixed
     */
    public function title(Request $request)
    {
        return $this->getValue($request, $this->title);
    }

    /**
     * Recupera instancia del modelo del recurso
     * @return Model
     */
    public function model()
    {
        return $this->modelObject;
    }

    /**
     * Genera objecto del modelo del recurso
     * @return Resource
     */
    public function makeModelObject(Request $request)
    {
        if (is_null($this->modelObject)) {
            $this->modelObject = (new $this->model)
                ->setPerPage(empty($request->PerPage) ? $this->perPage : $request->PerPage);
        }

        return $this;
    }

    /**
     * Agrega una instancia del modelo al recurso
     * @param  Model|null $model
     * @return Resource
     */
    public function injectModel(Model $model = null)
    {
        $this->modelObject = is_null($model) ? new $this->model : $model;

        return $this;
    }

    /**
     * Agrega un listado de instancias de modelos al recurso
     * @param  Collection|null $modelList
     * @return Resource
     */
    public function injectModelList(Collection $modelList = null)
    {
        $this->modelList = $modelList;

        return $this;
    }

    /**
     * Agrega condiciones de filtro a objecto modelo
     * @param  Request $request
     * @return Resource
     */
    public function resourceFilter(Request $request)
    {
        if (empty($request->input($this->filterKey))) {
            return $this;
        }

        foreach($this->search as $field) {
            $this->modelObject = $this->modelObject
                ->orWhere($field, 'like', '%'.$request->input($this->filterKey).'%');
        };

        return $this;
    }

    /**
     * Devuelve orden del modelo
     * @return array Arreglo con campos de ordenamiento
     */
    public function getOrder()
    {
        if (!is_array($this->orderBy)) {
            $this->orderBy = [(string) $this->orderBy => 'asc'];
        }

        return $this->orderBy;
    }

    /**
     * Agrega condiciones order-by a objeto del modelo
     * @param  Request $request
     * @return Resource
     */
    public function resourceOrderBy(Request $request)
    {
        $orderBy = $request->has($this->sortByKey)
            ? [$request->input($this->sortByKey) => $request->input($this->sortDirectionKey, 'asc')]
            : $this->getOrder();

        foreach ($orderBy as $field => $order) {
            $this->modelObject = $this->modelObject->orderBy($field, $order);
        }

        return $this;
    }

    /**
     * Recupera el valor de un campo
     * @param  Request $request
     * @param  string  $field   Campo a recuperar
     * @return mixed
     */
    public function getValue(Request $request, $field = '')
    {
        $fieldObject = collect($this->fields($request))
            ->first(function($fieldObject) use ($field) {
                return $fieldObject->getField() === $field;
            });

        if (is_null($fieldObject)) {
            return null;
        }

        return $fieldObject->getValue($request, $this->modelObject);
    }

    /**
     * Devuelve campos a mostrar en listado
     * @param  Request $request
     * @return array
     */
    public function indexFields(Request $request)
    {
        return collect($this->fields($request))
            ->filter(function($field) {
                return $field->showOnIndex();
            })
            ->all();
    }

    /**
     * Devuelve campos a mostrar en detalle y formularios
     * @param  Request $request
     * @return array
     */
    public function detailFields(Request $request)
    {
        return collect($this->fields($request))
            ->filter(function($field) {
                return $field->showOnDetail();
            })
            ->all();
    }

    /**
     * Devuelve arreglo de validacion del recurso
     * @param  Request $request
     * @return array
     */
    public function getValidation(Request $request)
    {
        $resource = $this;

        return collect($this->fields($request))
            ->mapWithKeys(function($field) use ($resource) {
                return [$field->getField($resource) => $field->getValidation($resource)];
            })
            ->all();
    }

    /**
     * Devuelve paginador del modelo
     * @return Paginator
     */
    public function getPaginated(Request $request)
    {
        $paginate = $this->modelObject->paginate();

        $this->modelObject = null;
        $this->makeModelObject($request);

        return $paginate;
    }

    /**
     * Devuelve listado de modelos
     * @return Collection
     */
    public function getModelList()
    {
        return $this->modelList;
    }

    /**
     * Genera listado de modelos ordenados y filtrados
     * @param  Request $request
     * @return Collection
     */
    public function modelList(Request $request)
    {
        $this->modelList = $this->makeModelObject($request)
            ->resourceOrderBy($request)
            ->resourceFilter($request)
            ->applyFilters($request)
            ->getPaginated($request);

        return $this->modelList;
    }

    /**
     * Genera links de paginacion de un listado de modelos
     * @param  Request $request
     * @return HtmlString
     */
    public function getPaginationLinks(Request $request)
    {
        return $this->modelList
            ->appends($request->all())
            ->links();
    }

    public function getModelAjaxFormOptions($where = [])
    {
        return ajax_options($this->getModelFormOptions($where));
    }

    public function getModelFormOptions($where = [])
    {
        $query = $this->modelObject;

        $whereIn = collect($where)->filter(function ($elem, $key) {
            return !is_integer($key) and is_array($elem);
        });

        $whereValue = collect($where)->filter(function ($elem, $key) {
            return is_integer($key) or !is_array($elem);
        })->all();

        $query = $query->where($whereValue);

        if (! $whereIn->isEmpty()) {
            $whereIn->each(function ($elem, $key) use (&$query) {
                return $query->whereIn($key, $elem);
            });
        }

        $resource = $this;
        return $query->get()->mapWithKeys(function ($model) use ($resource) {
            return [$model->getKey() => $resource->title()];
        });
    }

    public function findOrFail($modelId)
    {
        $this->injectModel($this->modelObject->findOrFail($modelId));

        return $this;
    }

    public function findOrNew($modelId)
    {
        $this->injectModel($this->modelObject->findOrNew($modelId));

        return $this;
    }

    public function update(Request $request)
    {
        // actualiza el objeto
        $this->modelObject->update($request->all());

        // actualiza las tablas relacionadas
        collect($this->fields($request))
            // filtra los campos de TIPO_HAS_MANY
            ->filter(function($elem) {
                return get_class($elem) === HasMany::class;
            })
            // Sincroniza la tabla relacionada
            ->each(function ($field) use ($request) {
                $this->modelObject->{$field->getField()}()
                    ->sync($request->input($field->getField(), []));
            });
    }

}
