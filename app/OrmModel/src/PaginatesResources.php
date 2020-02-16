<?php

namespace App\OrmModel\src;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait PaginatesResources
{
    protected $paginator = null;
    protected $resourceList = null;
    protected $paginationLinksDetail = false;

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
        return $this->paginator = $this->resourceSetPerPage($request)
            ->resourceOrderBy($request)
            ->resourceFilter($request)
            ->applyFilters($request)
            ->getBelongsToRelations($request)
            ->getPaginated($request);
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
            ->map->indexFields($request);

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
     * @return boolean
     */
    public function paginationLinksDetail(): bool
    {
        return $this->paginationLinksDetail;
    }
}
