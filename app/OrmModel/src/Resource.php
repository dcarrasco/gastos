<?php

namespace App\OrmModel\src;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

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

    protected $modelInstance = null;

    protected $perPage = 25;

    protected $fields;


    /**
     * Constructor del recurso
     *
     * @param Model $modelInstance
     */
    public function __construct(Model $modelInstance = null)
    {
        if ($this->model === '') {
            throw new \Exception('Modelo no definido en recurso OrmModel!');
        }

        $this->modelInstance = $modelInstance ?: $this->makeModelInstance();
        $this->modelQueryBuilder = $this->modelInstance->newQuery();
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
        return class_basename($this);
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
     * @return string
     */
    public function title(): string
    {
        return optional($this->modelInstance)->{$this->title} ?? '';
    }

    /**
     * Recupera instancia del modelo del recurso
     *
     * @return Model
     */
    public function model(): Model
    {
        return $this->modelInstance;
    }

    /**
     * Genera objecto del modelo del recurso
     *
     * @return Model
     */
    public function makeModelInstance(): Model
    {
        return new $this->model();
    }

    /**
     * Devuelve los campos ya resueltos
     *
     * @return Collection
     */
    public function getFields(): Collection
    {
        return $this->fields ?? collect([]);
    }

    /**
     * Resuelve los valores de los campos del modelo
     * @param  Model   $model
     * @param  Request $request
     * @return Resource
     */
    protected function resolveFieldValues(Model $model, Request $request): Resource
    {
        $this->fields = collect($this->fields($request))
            ->map->resolveValue($this->modelInstance, $request);

        return $this;
    }

    /**
     * Devuelve campos a mostrar en listado
     *
     * @param  Request $request
     * @return Resource
     */
    public function resolveIndexFields(Request $request): Resource
    {
        $this->fields = collect($this->fields($request))
            ->filter->showOnIndex()
            ->map->makeSortingIcon($request, $this)
            ->map->resolveValue($this->modelInstance, $request)
            ->map->resolveFormattedValue();

        return $this;
    }

    /**
     * Devuelve campos a mostrar en detalle
     *
     * @param  Request $request
     * @return Resource
     */
    public function resolveDetailFields(Request $request): Resource
    {
        $this->fields = collect($this->fields($request))
            ->filter->showOnDetail()
            ->map->resolveValue($this->modelInstance, $request)
            ->map->resolveFormattedValue();

        return $this;
    }

    /**
     * Devuelve campos a mostrar en formularios
     *
     * @param  Request $request
     * @return Resource
     */
    public function resolveFormFields(Request $request): Resource
    {
        $this->fields = collect($this->fields($request))
            ->filter->showOnForm()
            ->map->resolveValue($this->modelInstance, $request)
            ->map->resolveFormItem($request, $this);

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
    public function eagerLoadsRelations(Request $request): Resource
    {
        $this->modelQueryBuilder = $this->modelQueryBuilder->with(
            collect($this->fields($request))
                ->filter->eagerLoadsRelation()
                ->map->getAttribute()
                ->all()
        );

        return $this;
    }

    /**
     * Devuelve recurso como opciones de un formulario en llamadas ajax
     *
     * @param  Request $request
     * @return string
     */
    public function getModelAjaxFormOptions(Request $request): string
    {
        return $this->getModelFormOptions($request)
            ->map(function ($value, $key) {
                return "<option value=\"{$key}\">" . e($value) . "</option>";
            })
            ->implode('');
    }

    /**
     * Devuelve recurso como Collection para formulario
     *
     * @param  Request $request
     * @return Collection
     */
    public function getModelFormOptions(Request $request): Collection
    {
        $this->applyOrderBy($request);

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
            return [$model->getKey() => (new static($model))->title()];
        });
    }
}
