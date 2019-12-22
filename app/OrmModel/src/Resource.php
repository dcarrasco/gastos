<?php

namespace App\OrmModel\src;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\OrmModel\src\OrmField\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class Resource
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
    protected $paginatedResources = null;
    protected $paginationLinksDetail = false;

    protected $perPage = 25;

    /**
     * Constructor del recurso
     *
     * @param Model $modelObject
     */
    public function __construct(Model $modelObject = null)
    {
        if ($this->model === '') {
            throw new \Exception('Modelo no definido en recurso OrmModel!');
        }

        $this->modelObject = $modelObject ?: $this->makeModelObject();
        $this->modelQueryBuilder = $this->modelObject->newQuery();
    }

    /**
     * Campos del recurso
     * 
     * @param  Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        return [];
    }

    /**
     * Recupera nombre del recurso
     * 
     * @return string
     */
    public function getName(): string
    {
        $fullName = explode("\\", get_class($this));
        return array_pop($fullName);
    }

    /**
     * Devuelve descripcion del recurso
     * 
     * @return string
     */
    public function getLabel(): string
    {
        return empty($this->label) ? class_basename($this) : $this->label;
    }

    /**
     * Devuelve descripcion del recurso en plural
     * 
     * @return string
     */
    public function getLabelPlural(): string
    {
        return empty($this->labelPlural) ? Str::plural($this->getLabel()) : $this->labelPlural;
    }

    /**
     * Devuelve la representación del recurso
     * 
     * @param  Request $request
     * @return mixed
     */
    public function title(): string
    {
        return optional($this->modelObject)->{$this->title} ?? '';
    }

    /**
     * Recupera instancia del modelo del recurso
     * 
     * @return Model
     */
    public function model(): Model
    {
        return $this->modelObject;
    }

    /**
     * Genera objecto del modelo del recurso
     * 
     * @return Resource
     */
    public function makeModelObject(): Model
    {
        return (new $this->model);
    }

    /**
     * Agrega una instancia del modelo al recurso
     * 
     * @param  Model|null $model
     * @return Resource
     */
    public function injectModel(Model $model = null): Resource
    {
        $this->modelObject = is_null($model) ? new $this->model : $model;

        return $this;
    }

    /**
     * Devuelve campos a mostrar en listado
     * 
     * @param  Request $request
     * @return array
     */
    public function indexFields(Request $request): array
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
     * 
     * @param  Request $request
     * @return array
     */
    public function detailFields(Request $request): Collection
    {
        return collect($this->fields($request))
            ->filter->showOnDetail()
            ->map->resolveValue($this->modelObject, $request);
    }

    /**
     * Devuelve campos a mostrar en formularios
     * 
     * @param  Request $request
     * @return array
     */
    public function formFields(Request $request): Collection
    {
        return collect($this->fields($request))
            ->filter->showOnForm()
            ->map->resolveFormItem($request, $this, ['class' => 'form-control']);
    }

    /**
     * Devuelve arreglo de validacion del recurso
     * 
     * @param  Request $request
     * @return array
     */
    public function getValidation(Request $request): array
    {
        return collect($this->fields($request))
            ->mapWithKeys(function($field) {
                return [$field->getModelAttribute($this) => $field->getValidation($this)];
            })
            ->all();
    }

    /**
     * Agrega a la query los nombres de las relaciones para traer campos relacionados
     *
     * @param Request $request
     * @return Resource
     */
    public function getBelongsToRelations(Request $request): Resource
    {
        $query = $this->modelQueryBuilder;

        collect($this->fields($request))
            ->filter(function($field) {
                return get_class($field) === BelongsTo::class;
            })
            ->map->getAttribute()
            ->each(function($relatedClass) use (&$query) {
                $query = $query->with($relatedClass);
            });

        $this->modelQueryBuilder = $query;

        return $this;
    }

    /**
     * Devuelve paginador del modelo
     * 
     * @return Paginator
     */
    public function getPaginated(Request $request): LengthAwarePaginator
    {
        $paginate = $this->modelQueryBuilder->paginate();

        $this->modelQueryBuilder = $this->makeModelObject($request)->newQuery();

        return $paginate;
    }

    /**
     * Devuelve el paginador del recurso
     * 
     * @return paginador
     */
    public function getPaginator(): LengthAwarePaginator
    {
        return $this->paginator;
    }

    /**
     * Genera listado de modelos ordenados y filtrados
     * 
     * @param  Request $request
     * @return Collection
     */
    public function paginator(Request $request): LengthAwarePaginator
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
     * Genera paginador del recurso y listado de recursos de la pagina
     * 
     * @param  Request $request
     * @return Resource
     */
    public function makePaginatedResources(Request $request): Resource
    {
        $this->paginatedResources = $this->paginator($request)
            ->getCollection()
            ->mapInto($this)
            ->map->indexFields($request);

        return $this;
    }

    /**
     * Devuelve listado de recursos del paginador
     * 
     * @return Collection
     */
    public function getPaginatedResources(): Collection
    {
        return $this->paginatedResources;
    }

    /**
     * Devuelve propiedad de detalle de links del paginador del recurso
     * 
     * @return boolean
     */
    public function paginationLinksDetail(): bool
    {
        return $this->paginationLinksDetail;
    }

    public function getModelAjaxFormOptions(Request $request): string
    {
        return ajax_options($this->getModelFormOptions($request));
    }

    public function getModelFormOptions(Request $request): Collection
    {
        $this->modelObject = $this->makeModelObject();
        $this->resourceOrderBy($request);

        $whereIn = collect($request->all())->filter(function ($elem, $key) {
            return !is_integer($key) and is_array($elem);
        });

        $whereValue = collect($request->all())->filter(function ($elem, $key) {
            return is_integer($key) or !is_array($elem);
        })->all();

        $query = $this->modelQueryBuilder->where($whereValue);
        $whereIn->each(function ($elem, $key) use (&$query) {
            return $query->whereIn($key, $elem);
        });

        return $query->get()->mapWithKeys(function ($model) use ($request) {
            return [$model->getKey() => $this->injectModel($model)->title()];
        });
    }
}
