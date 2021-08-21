<?php

namespace App\OrmModel\src;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait PaginatesResources
{
    /** @var LengthAwarePaginator */
    protected $paginator = null;

    /** @var Collection */
    protected $resourceList = null;

    /** @var bool */
    protected $paginationLinksDetail = false;

    /**
     * Devuelve paginador del modelo
     *
     * @return LengthAwarePaginator
     */
    public function getPaginated(): LengthAwarePaginator
    {
        return $this->modelQueryBuilder->paginate();
    }

    /**
     * Devuelve el paginador del recurso
     *
     * @return LengthAwarePaginator
     */
    public function getPaginator(): LengthAwarePaginator
    {
        return $this->paginator;
    }

    /**
     * Genera listado de modelos ordenados y filtrados
     *
     * @param  Request $request
     * @return LengthAwarePaginator
     */
    public function paginator(Request $request): LengthAwarePaginator
    {
        return $this->paginator = $this->resourceSetPerPage($request)
            ->applyOrderBy($request)
            ->applySearchFilter($request)
            ->applyFilters($request)
            ->eagerLoadsRelations($request)
            ->getPaginated();
    }

    /**
     * Genera paginador del recurso y listado de recursos de la pagina
     *
     * @param  Request $request
     * @return Resource
     */
    public function makePaginatedResources(Request $request): Resource
    {
        $this->resourceList = $this->paginator($request)
            ->getCollection()
            ->mapInto($this)
            ->map->resolveIndexFields($request);

        return $this;
    }

    /**
     * Devuelve listado de recursos del paginador
     *
     * @return Collection
     */
    public function resourceList(): Collection
    {
        return $this->resourceList;
    }

    /**
     * Devuelve propiedad de detalle de links del paginador del recurso
     *
     * @return bool
     */
    public function paginationLinksDetail(): bool
    {
        return $this->paginationLinksDetail;
    }
}
