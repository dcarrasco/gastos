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

    protected $sortIconDefault = 'fa fa-sort text-gray-400';
    protected $sortIcons = [
        'asc' => 'fa fa-caret-up',
        'desc' => 'fa fa-caret-down',
    ];
    protected $newSortOrder = ['asc' => 'desc', 'desc' => 'asc'];

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
            $field = $request->input($this->sortByKey, collect($resource->getOrderBy())->keys()->first());
            $order = $request->input($this->sortDirectionKey, collect($resource->getOrderBy())->first());

            $iconClass = $this->getSortingIconClass($field, $order);
            $iconOrder = $this->getSortingOrder($field, $order);
            $sortUrl = $this->getSortUrl($request, $iconOrder);

            $this->sortingIcon = new HtmlString("<a href=\"{$sortUrl}\"><span class=\"{$iconClass}\"><span></a>");
        }

        return $this;
    }

    /**
     * Devuelve la clase o icono a aplicar en un campo
     *
     * @param  string  $field
     * @param  string  $order
     * @return string
     */
    protected function getSortingIconClass(string $field, string $order): string
    {
        return ($field === $this->attribute)
            ? Arr::get($this->sortIcons, $order, $this->sortIconDefault)
            : $this->sortIconDefault;
    }

    /**
     * Devuelve el orden (asc/desc) de ordenamiento de un campo
     *
     * @param  string  $field
     * @param  string  $order
     * @return string
     */
    protected function getSortingOrder(string $field, string $order): string
    {
        return ($field === $this->attribute)
            ? Arr::get($this->newSortOrder, $order, 'asc')
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
