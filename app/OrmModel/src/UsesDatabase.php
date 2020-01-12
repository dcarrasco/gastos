<?php

namespace App\OrmModel\src;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\OrmField\HasMany;
use Illuminate\Database\Eloquent\Builder;

trait UsesDatabase
{
    public $orderBy = [];

    protected $modelQueryBuilder;

    protected $sortByKey = 'sort-by';
    protected $sortDirectionKey = 'sort-direction';
    protected $filterKey = 'filtro';

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
    public function resourceFilter(Request $request): Resource
    {
        if (empty($request->input($this->filterKey))) {
            return $this;
        }

        foreach($this->search as $field) {
            $this->modelQueryBuilder = $this->modelQueryBuilder
                ->orWhere($field, 'like', '%'.$request->input($this->filterKey).'%');
        };

        return $this;
    }

    /**
     * Devuelve orden del modelo
     *
     * @return array Arreglo con campos de ordenamiento
     */
    public function getOrder(): array
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
        if (is_null($this->modelObject)) {
            $this->modelObject = $this->makeModelObject();
        }

        $this->modelObject
            ->setPerPage($request->PerPage ?: $this->perPage);

        return $this;
    }

    /**
     * Agrega condiciones order-by a objeto del modelo
     *
     * @param  Request $request
     * @return Resource
     */
    public function resourceOrderBy(Request $request): Resource
    {
        $orderBy = $request->has($this->sortByKey)
            ? [$request->input($this->sortByKey) => $request->input($this->sortDirectionKey, 'asc')]
            : $this->getOrder();

        foreach ($orderBy as $field => $order) {
            $this->modelQueryBuilder = $this->modelQueryBuilder->orderBy($field, $order);
        }

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
        $this->injectModel($this->modelObject->findOrFail($modelId));

        return $this;
    }

    /**
     * Recupera modelo a partir de un ID, o genera uno en blanco
     *
     * @param string $modelId
     * @return Resource
     */
    public function findOrNew(string $modelId): Resource
    {
        $this->injectModel($this->modelObject->findOrNew($modelId));

        return $this;
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
        $this->modelObject->update($request->all());

        // actualiza las tablas relacionadas
        collect($this->fields($request))
            // filtra los campos de TIPO_HAS_MANY
            ->filter(function($elem) {
                return get_class($elem) === HasMany::class;
            })
            // Sincroniza la tabla relacionada
            ->each(function ($field) use ($request) {
                $this->modelObject->{$field->getAttribute()}()
                    ->sync($request->input($field->getAttribute(), []));
            });

        return $this;
    }
}
