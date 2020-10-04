<?php

namespace App\OrmModel\src\OrmField;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;

trait UsesSorting
{
    protected $isSortable = false;

    protected $sortByKey = 'sort-by';
    protected $sortDirectionKey = 'sort-direction';
    protected $sortIconDefault = 'fa fa-sort';
    protected $sortIcons = [
        'asc' => 'fa fa-caret-up text-muted',
        'desc' => 'fa fa-caret-down text-muted',
    ];

    protected $sortingIcon;

    /**
     * Establece que el campo es "ordenable"
     *
     * @return Field
     */
    public function sortable(): Field
    {
        $this->isSortable = true;

        return $this;
    }

    /**
     * Devuelve icono de ordenamiento
     *
     * @return HtmlString
     */
    public function sortingIcon(): HtmlString
    {
        return $this->sortingIcon ?? new HtmlString('');
    }

    /**
     * Genera iconos para ordenar por campo en listado Index
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @return Field
     */
    public function makeSortingIcon(Request $request, Resource $resource): Field
    {
        if ($this->isSortable) {
            $iconClass = $this->getSortingIconClass($request, $resource);
            $sortOrder = $this->getSortingOrder($request, $resource);
            $sortUrl = $this->getSortUrl($request, $sortOrder);

            $this->sortingIcon = new HtmlString("<a href=\"{$sortUrl}\"><span class=\"{$iconClass}\"><span></a>");
        }

        return $this;
    }

    /**
     * Devuelve la clase o icono a aplicar en un campo
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @return string
     */
    protected function getSortingIconClass(Request $request, Resource $resource): string
    {
        $sortingField = $request->input($this->sortByKey, collect($resource->getOrderBy())->keys()->first());
        $sortDirection = $request->input($this->sortDirectionKey, collect($resource->getOrderBy())->first());

        return ($sortingField === $this->attribute)
            ? Arr::get($this->sortIcons, $sortDirection, $this->sortIconDefault)
            : $this->sortIconDefault;
    }

    /**
     * Devuelve el orden (asc/desc) de ordenamiento de un campo
     *
     * @param  Request  $request
     * @param  Resource $resource
     * @return string
     */
    protected function getSortingOrder(Request $request, Resource $resource): string
    {
        $sortingField = $request->input($this->sortByKey, collect($resource->getOrderBy())->keys()->first());
        $sortDirection = $request->input($this->sortDirectionKey, collect($resource->getOrderBy())->first());
        $newSortOrder = ['asc' => 'desc', 'desc' => 'asc'];

        return ($sortingField === $this->attribute)
            ? Arr::get($newSortOrder, $sortDirection, 'asc')
            : 'asc';
    }

    /**
     * Devuelve URL de ordenamiento
     *
     * @param  Request $request
     * @param  string  $sortOrder
     * @return string
     */
    protected function getSortUrl(Request $request, string $sortOrder = ''): string
    {
        return $request->fullUrlWithQuery(array_merge($request->all(), [
            $this->sortByKey => $this->attribute,
            $this->sortDirectionKey => $sortOrder,
        ]));
    }
}
