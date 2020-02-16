<?php

namespace App\OrmModel\src;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\OrmModel\src\OrmField\BelongsTo;

abstract class Resource
{
    use UsesCards;
    use UsesFilters;
    use UsesDatabase;
    use PaginatesResources;

    public $model = '';
    public $label = '';
    public $labelPlural = '';
    public $icono = 'table';
    public $title = 'id';

    public $search = ['id'];

    protected $modelObject = null;

    protected $perPage = 25;

    protected $fields;


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
        return new $this->model();
    }

    /**
     * Agrega una instancia del modelo al recurso
     *
     * @param  Model|null $model
     * @return Resource
     */
    public function injectModel(Model $model = null): Resource
    {
        $this->modelObject = is_null($model) ? $this->makeModelObject() : $model;

        return $this;
    }

    /**
     * Devuelve los campos ya resueltos
     *
     * @return Collection
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    /**
     * Devuelve campos a mostrar en listado
     *
     * @param  Request $request
     * @return array
     */
    public function indexFields(Request $request): Resource
    {
        $this->fields = collect($this->fields($request))
            ->filter->showOnIndex()
            ->map->makeSortingIcon($request, $this)
            ->map->resolveValue($this->modelObject, $request);

        return $this;
    }

    /**
     * Devuelve campos a mostrar en detalle
     *
     * @param  Request $request
     * @return array
     */
    public function detailFields(Request $request): Resource
    {
        $this->fields = collect($this->fields($request))
            ->filter->showOnDetail()
            ->map->resolveValue($this->modelObject, $request);

        return $this;
    }

    /**
     * Devuelve campos a mostrar en formularios
     *
     * @param  Request $request
     * @return array
     */
    public function formFields(Request $request): Resource
    {
        $this->fields = collect($this->fields($request))
            ->filter->showOnForm()
            ->map->resolveFormItem($request, $this, ['class' => 'form-control']);

        return $this;
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
            ->mapWithKeys(function ($field) {
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
        collect($this->fields($request))
            ->filter(function ($field) {
                return get_class($field) === BelongsTo::class;
            })
            ->map->getAttribute()
            ->each(function ($relatedClass) {
                $this->modelQueryBuilder = $this->modelQueryBuilder->with($relatedClass);
            });

        return $this;
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
