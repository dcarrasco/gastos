<?php

namespace App\Http\Controllers\Orm;

use App\OrmModel\src\Filters\PerPage;
use App\OrmModel\src\Metrics\Metric;
use App\OrmModel\src\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

trait OrmControllerHelper
{
    /**
     * Devuelve un recurso
     *
     * @param  string  $resourceName Nombre del recurso a recuperar
     * @param  string  $resourceId   Id del recurso a recuperar
     * @return resource
     */
    protected function getResource(string $resourceName = '', $resourceId = null): Resource
    {
        $resource = $this->getMenuModulo()
            ->first(fn ($resourceItem) => $resourceItem->getName() === $resourceName)
            ?? $this->getMenuModulo()->first();

        if ($resourceId) {
            return $resource->findOrFail($resourceId);
        }

        return $resource;
    }

    /**
     * Devuelve instancias de menuModulo
     *
     * @return Collection<array-key, resource>
     */
    protected function getMenuModulo(): Collection
    {
        return collect($this->menuModulo)
            ->map(function ($resourceName) {
                /** @var resource */
                $newResource = new $resourceName();

                return $newResource;
            });
    }

    /**
     * Genera las rutas web del configuración del módulo
     *
     * @param  string  $modulo
     * @param  string  $controllerClass
     * @return void
     */
    public static function routes(string $modulo, string $controllerClass): void
    {
        $modulo = strtolower($modulo);

        Route::group(
            ['prefix' => "{$modulo}-config", 'as' => "{$modulo}Config.", 'middleware' => 'auth'],
            function () use ($controllerClass) {
                Route::controller($controllerClass)->group(function () {
                    Route::get('ajaxCard', 'ajaxCard')->name('ajaxCard');
                    Route::get('{modelName?}', 'index')->name('index');
                    Route::get('{modelName}/create', 'create')->name('create');
                    Route::post('{modelName}', 'store')->name('store');
                    Route::get('{modelName}/{modelID}/show', 'show')->name('show');
                    Route::get('{modelName}/{modelID}/edit', 'edit')->name('edit');
                    Route::put('{modelName}/{modelID}', 'update')->name('update');
                    Route::delete('{modelName}/{modelID}', 'destroy')->name('destroy');
                    Route::get('{modelName}/ajax-form', 'ajaxOnChange')->name('ajaxOnChange');
                });
            }
        );
    }

    /**
     * Genera menu
     *
     * @return Collection<array-key, \stdClass>
     */
    public function makeMenuModuloURL(string $selectedResource): Collection
    {
        return $this->getMenuModulo()
            ->map(fn ($resource) => (object) [
                'nombre' => $resource->getLabelPlural(),
                'url' => route("{$this->routeName}.index", $resource->getName()),
                'selected' => $resource->getName() === $selectedResource,
            ]);
    }

    /**
     * Agrega variables a desplegar en vistas
     *
     * @return void
     */
    protected function makeView(Request $request): void
    {
        $selectedResource = $request->route()
            ? (string) ($request->route('modelName') ?? $this->getMenuModulo()->first()->getName())
            : '';

        view()->share('perPageFilter', new PerPage());
        view()->share('menuModulo', $this->makeMenuModuloURL($selectedResource));
        view()->share('routeName', $this->routeName);
        view()->share('moduloSelected', $selectedResource);
    }

    /**
     * Devuelve mensaje de alerta para desplegar al realizar una accion
     *
     * @param  string  $message
     * @param  resource  $resource
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
     * @param  Request  $request
     * @return Metric[]
     */
    protected function cards(Request $request): array
    {
        return $this->getMenuModulo()
            ->flatMap->cards($request)
            ->all();
    }
}
