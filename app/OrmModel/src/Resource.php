<?php

namespace App\OrmModel\src;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Resource
{
    use UsesFilters;
    use UsesDatabase;
    use UsesCards;

    public $model = '';
    public $label = '';
    public $labelPlural = '';
    public $icono = 'table';
    public $title = 'id';

    public $search = ['id'];

    protected $modelObject = null;
    protected $paginator = null;
    protected $paginationResources = [];
    protected $paginationLinksDetail = false;

    protected $perPage = 25;

    public function __construct($modelObject = null)
    {
        if ($this->model === '') {
            throw new \Exception('Modelo no definido en recurso OrmModel!');
        }

        $this->modelObject = $modelObject ?: $this->makeModelObject();
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
     * Cards del recurso
     * @param  Request $request
     * @return array
     */
    public function cards(Request $request)
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
        return empty($this->label) ? class_basename($this) : $this->label;
    }

    /**
     * Devuelve descripcion del recurso en plural
     * @return string
     */
    public function getLabelPlural()
    {
        return empty($this->labelPlural) ? Str::plural($this->getLabel()) : $this->labelPlural;
    }

    /**
     * Devuelve la representación del recurso
     * @param  Request $request
     * @return mixed
     */
    public function title()
    {
        return optional($this->modelObject)->{$this->title};
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
    public function makeModelObject()
    {
        return (new $this->model);
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
     * Devuelve campos a mostrar en listado
     * @param  Request $request
     * @return array
     */
    public function indexFields(Request $request)
    {
        return [
            'resource' => $this,
            'fields' => collect($this->fields($request))
                ->filter->showOnIndex()
                ->map->makeSortingIcon($request, $this)
                ->map->resolveValue($this->modelObject, $request)
        ];
    }

    /**
     * Devuelve campos a mostrar en detalle
     * @param  Request $request
     * @return array
     */
    public function detailFields(Request $request)
    {
        return collect($this->fields($request))
            ->filter->showOnDetail()
            ->map->resolveValue($this->modelObject, $request);
    }

    /**
     * Devuelve campos a mostrar en formularios
     * @param  Request $request
     * @return array
     */
    public function formFields(Request $request)
    {
        return collect($this->fields($request))
            ->filter->showOnForm()
            ->map->resolveFormItem($request, $this, ['class' => 'form-control']);
    }

    /**
     * Devuelve arreglo de validacion del recurso
     * @param  Request $request
     * @return array
     */
    public function getValidation(Request $request)
    {
        return collect($this->fields($request))
            ->mapWithKeys(function($field) {
                return [$field->getModelAttribute($this) => $field->getValidation($this)];
            })
            ->all();
    }

    public function getBelongsToRelations(Request $request)
    {
        $belongsToRelations = collect($this->fields($request))
            ->filter(function($field) {
                return get_class($field) === BelongsTo::class;
            })
            ->map->getAttribute()
            ->toArray();

        if (count($belongsToRelations) > 0) {
            $this->modelObject = $this->modelObject->with($belongsToRelations);
        }

        return $this;
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
     * Genera paginador del recurso y listado de recursos de la pagina
     * @param  Request $request
     * @return Resource
     */
    public function makePaginator(Request $request)
    {
        $this->paginationResources = $this->paginator($request)
            ->getCollection()
            ->mapInto($this)
            ->map->indexFields($request);

        return $this;
    }

    /**
     * Devuelve el paginador del recurso
     * @return paginador
     */
    public function getPaginator()
    {
        return $this->paginator;
    }


    /**
     * Genera listado de modelos ordenados y filtrados
     * @param  Request $request
     * @return Collection
     */
    public function paginator(Request $request)
    {
        return is_null($this->paginator)
            ? $this->paginator = $this->resourceSetPerPage($request)
                ->resourceOrderBy($request)
                ->resourceFilter($request)
                ->applyFilters($request)
                ->getBelongsToRelations($request)
                ->getPaginated($request)
            : $this->paginator;
    }

    /**
     * Devuelve listado de recursos del paginador
     * @return Collection
     */
    public function getPaginationResources()
    {
        return $this->paginationResources;
    }

    /**
     * Devuelve propiedad de detalle de links del paginador del recurso
     * @return boolean
     */
    public function paginationLinksDetail()
    {
        return $this->paginationLinksDetail;
    }

    public function getModelAjaxFormOptions(Request $request)
    {
        return ajax_options($this->getModelFormOptions($request));
    }

    public function getModelFormOptions(Request $request)
    {
        $this->modelObject = $this->makeModelObject();
        $this->resourceOrderBy($request);

        $whereIn = collect($request->all())->filter(function ($elem, $key) {
            return !is_integer($key) and is_array($elem);
        });

        $whereValue = collect($request->all())->filter(function ($elem, $key) {
            return is_integer($key) or !is_array($elem);
        })->all();

        $query = $this->modelObject->where($whereValue);
        if (! $whereIn->isEmpty()) {
            $whereIn->each(function ($elem, $key) use (&$query) {
                return $query->whereIn($key, $elem);
            });
        }

        return $query->get()->mapWithKeys(function ($model) use ($request) {
            return [$model->getKey() => $this->injectModel($model)->title()];
        });
    }
}
