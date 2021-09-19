<?php

namespace App\Http\Controllers\Orm;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Collection;
use App\OrmModel\src\Metrics\Metric;
use App\OrmModel\src\Filters\PerPage;
use Illuminate\Support\Facades\Route;

trait OrmControllerHelper
{
    /**
     * Devuelve un recurso
     *
     * @param  string  $resourceName Nombre del recurso a recuperar
     * @param  string  $resourceId   Id del recurso a recuperar
     * @return Resource
     */
    protected function getResource(string $resourceName = '', $resourceId = null): Resource
    {
        $resource = collect($this->menuModulo)
            ->first(fn($resource) => $resource->getName() === $resourceName)
            ?? collect($this->menuModulo)->first();

        if ($resourceId) {
            return $resource->findOrFail($resourceId);
        }

        return $resource;
    }

    /**
     * Genera las rutas web del configuración del módulo
     *
     * @param  string $modulo
     * @param  string $controllerClass
     * @return void
     */
    public static function routes(string $modulo, string $controllerClass): void
    {
        $modulo = strtolower($modulo);
        $prefix = "{$modulo}-config";
        $as = "{$modulo}Config.";

        Route::group(
            ['prefix' => $prefix, 'as' => $as, 'middleware' => 'auth'],
            function () use ($controllerClass) {
                Route::get('ajaxCard', [$controllerClass, 'ajaxCard'])->name('ajaxCard');
                Route::get('{modelName?}', [$controllerClass, 'index'])->name('index');
                Route::get('{modelName}/create', [$controllerClass, 'create'])->name('create');
                Route::post('{modelName}', [$controllerClass, 'store'])->name('store');
                Route::get('{modelName}/{modelID}/show', [$controllerClass, 'show'])->name('show');
                Route::get('{modelName}/{modelID}/edit', [$controllerClass, 'edit'])->name('edit');
                Route::put('{modelName}/{modelID}', [$controllerClass, 'update'])->name('update');
                Route::delete('{modelName}/{modelID}', [$controllerClass, 'destroy'])->name('destroy');
                Route::get('{modelName}/ajax-form', [$controllerClass, 'ajaxOnChange'])->name('ajaxOnChange');
            }
        );
    }

    /**
     * Genera menu
     *
     * @return Collection<mixed>
     */
    public function makeMenuModuloURL(string $selectedResource): Collection
    {
        return collect($this->menuModulo)
            ->map(fn($resource) => (object) [
                'nombre' => $resource->getLabelPlural(),
                'url' => route("{$this->routeName}.index", $resource->getName()),
                'selected' => $resource->getName() === $selectedResource ,
            ]);
    }

    /**
     * Agrega variables a desplegar en vistas
     * @return void
     */
    protected function makeView(Request $request): void
    {
        $selectedResource = $request->route()
            ? ($request->route('modelName') ?? collect($this->menuModulo)->first()->getName())
            : '';

        view()->share('perPageFilter', new PerPage());
        view()->share('menuModulo', $this->makeMenuModuloURL($selectedResource));
        view()->share('routeName', $this->routeName);
        view()->share('moduloSelected', $selectedResource);
    }

    /**
     * Devuelve mensaje de alerta para desplegar al realizar una accion
     * @param  string   $message
     * @param  Resource $resource
     * @param  Request  $request
     * @return string
     */
    protected function alertMessage(string $message, Resource $resource, Request $request): string
    {
        return trans($message, [
            'nombre_modelo' => $resource->getLabel(),
            'valor_modelo' => $resource->title(),
        ]);
    }

    /**
     * Recupera las cards de todos los modelos del controlador Orm
     *
     * @param  Request $request
     * @return Metric[]
     */
    protected function cards(Request $request): array
    {
        return collect($this->menuModulo)
            ->flatMap->cards($request)
            ->all();
    }
}
