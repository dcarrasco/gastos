<?php

namespace App\OrmModel\src;

use Illuminate\Http\Request;
use App\OrmModel\src\OrmField\HasMany;
use Illuminate\Database\Eloquent\Builder;

trait UsesDatabase
{
    /** @var string|array<string> */
    public $orderBy = [];

    /** @var Builder */
    protected $modelQueryBuilder;

    protected string $sortByKey = 'sort-by';
    protected string $sortDirectionKey = 'sort-direction';

    protected string $searchKey = 'search';

    /**
     * Devuelve nombre del parametro url
     *
     * @return string
     */
    public function urlSearchKey(): string
    {
        return $this->searchKey;
    }

    /**
     * Recupera la query del modelo
     *
     * @return Builder
     */
    public function getModelQueryBuilder(): Builder
    {
        return $this->modelQueryBuilder;
    }

    /**
     * Agrega condiciones de filtro a objecto modelo
     *
     * @param  Request $request
     * @return Resource
     */
    public function applySearchFilter(Request $request): Resource
    {
        $this->modelQueryBuilder = $this->modelQueryBuilder
            ->when($request->input($this->searchKey) ?? false, fn($query, $search) =>
                $query->where(fn($query) =>
                    collect($this->search)->each(fn($field) =>
                        $query->orWhere($field, 'like', "%{$search}%"))));

        return $this;
    }

    /**
     * Devuelve orden del modelo
     *
     * @return array<string>
     */
    public function getOrderBy(): array
    {
        if (!is_array($this->orderBy)) {
            $this->orderBy = [(string) $this->orderBy => 'asc'];
        }

        return $this->orderBy;
    }

    /**
     * Agrega limite de despliegue en listado
     *
     * @param  Request $request
     * @return Resource
     */
    public function resourceSetPerPage(Request $request): Resource
    {
        $this->modelInstance->setPerPage($request->input('PerPage') ?: $this->perPage);

        return $this;
    }

    /**
     * Agrega condiciones order-by a objeto del modelo
     *
     * @param  Request $request
     * @return Resource
     */
    public function applyOrderBy(Request $request): Resource
    {
        $orderBy = $request->has($this->sortByKey)
            ? [$request->input($this->sortByKey) => $request->input($this->sortDirectionKey, 'asc')]
            : $this->getOrderBy();

        collect($orderBy)->each(function ($order, $field) {
            $this->modelQueryBuilder->orderBy($field, $order);
        });

        return $this;
    }

    /**
     * Recupera modelo a partir de un ID, o falla
     *
     * @param string $modelId
     * @return Resource
     */
    public function findOrFail(string $modelId): Resource
    {
        return new static($this->modelInstance->findOrFail($modelId));
    }

    /**
     * Recupera modelo a partir de un ID, o genera uno en blanco
     *
     * @param string $modelId
     * @return Resource
     */
    public function findOrNew(string $modelId): Resource
    {
        return new static($this->modelInstance->findOrNew($modelId));
    }

    /**
     * Actualiza el modelo
     *
     * @param Request $request
     * @return Resource
     */
    public function update(Request $request): Resource
    {
        // actualiza el objeto
        $this->modelInstance->update($request->all());

        // actualiza las tablas relacionadas
        collect($this->fields($request))
            // filtra los campos de TIPO_HAS_MANY
            ->filter(fn($field) => get_class($field) === HasMany::class)
            // Sincroniza la tabla relacionada
            ->each(function ($field) use ($request) {
                $syncAttributes = $field->hasRelationFields()
                    ? collect($request->input($field->getAttribute()))
                        ->diff($request->input($field->getDeleteModelField()))
                        ->mapWithKeys(function ($value) use ($request, $field) {
                            $attribute = collect($field->getRelationFields())->keys()->first();
                            $attributeInput = "attributes:{$attribute}:{$value}";
                            $attributeRequest = json_encode($request->input($attributeInput, []));

                            return [$value => [$attribute => $attributeRequest]];
                        })
                    : $request->input($field->getAttribute(), []);

                $this->modelInstance->{$field->getAttribute()}()
                    ->sync($syncAttributes);
            });

        return $this;
    }
}
