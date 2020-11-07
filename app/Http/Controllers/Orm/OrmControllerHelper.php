<?php

namespace App\Http\Controllers\Orm;

use Route;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\Filters\PerPage;

trait OrmControllerHelper
{
    /**
     * Devuelve un recurso
     *
     * @param  string  $resourceName Nombre del recurso a recuperar
     * @param  Request $request
     * @return Resource
     */
    protected function getResource(string $resourceName = '', $resourceId = null): Resource
    {
        $resource = $this->menuModulo
            ->first(function ($resource) use ($resourceName) {
                return $resource->getName() === $resourceName;
            })
            ?? $this->menuModulo->first();

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
     * @return none
     */
    public static function routes(string $modulo, string $controllerClass)
    {
        $modulo = strtolower($modulo);

        $prefix = "{$modulo}-config";
        $as = "{$modulo}Config.";
        $namespace = ucfirst($modulo);

        Route::group(
            ['prefix' => $prefix, 'as' => $as, 'namespace' => $namespace, 'middleware' => 'auth'],
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
     * @return array
     */
    public function makeMenuModuloURL(string $selectedResource)
    {
        return $this->menuModulo
            ->map(function ($resource) use ($selectedResource) {
                return (object) [
                    'nombre' => $resource->getLabelPlural(),
                    'url' => route("{$this->routeName}.index", $resource->getName()),
                    'selected' => $resource->getName() === $selectedResource ,
                ];
            });
    }

    /**
     * Agrega variables a desplegar en vistas
     * @return
     */
    protected function makeView()
    {
        $selectedResource = Route::input('modelName') ?? $this->menuModulo->first()->getName();

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
            'valor_modelo' => $resource->title($request),
        ]);
    }

    /**
     * Recupera las cards de todos los modelos del controlador Orm
     *
     * @param  Request $request
     * @return array
     */
    protected function cards(Request $request): array
    {
        return $this->menuModulo
            ->flatMap->cards($request)
            ->all();
    }
}
