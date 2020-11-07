<?php

namespace App\Http\Controllers\Orm;

use Route;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\Http\Controllers\Controller;
use App\OrmModel\src\Filters\PerPage;

class OrmController extends Controller
{
    use OrmCard;

    protected $routeName = '';

    protected $menuModulo = [];


    public function __construct(Route $router)
    {
        if (empty($this->routeName)) {
            throw new \Exception("El parametro routeName no esta definido");
        }

        $this->menuModulo = collect($this->menuModulo)->map(function ($resource) {
            return new $resource();
        });

        $this->makeView();
    }

    /**
     * Genera menu
     *
     * @return array
     */
    public function makeMenuModuloURL(string $selectedResource)
    {
        return $this->menuModulo->map(function ($resource) use ($selectedResource) {
            return (object) [
                'nombre' => $resource->getLabelPlural(),
                'url' => route("{$this->routeName}.index", $resource->getName()),
                'selected' => $selectedResource === $resource->getName(),
            ];
        });
    }


    public function makeView()
    {
        $selectedResource = Route::input('modelName') ?? $this->menuModulo->first()->getName();

        view()->share('perPageFilter', new PerPage());
        view()->share('menuModulo', $this->makeMenuModuloURL($selectedResource));
        view()->share('routeName', $this->routeName);
        view()->share('moduloSelected', $selectedResource);
    }

    /**
     * Devuelve un recurso
     *
     * @param  string  $resourceName Nombre del recurso a recuperar
     * @param  Request $request
     * @return Resource
     */
    protected function getResource(string $resourceName = '', $resourceId = null): Resource
    {
        $resource = $this->menuModulo->first(function ($resource) use ($resourceName) {
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
     * @return none
     */
    public static function routes(string $modulo = '')
    {
        $modulo = strtolower($modulo);

        $prefix = "{$modulo}-config";
        $as = "{$modulo}Config.";
        $namespace = ucfirst($modulo);

        Route::group(
            ['prefix' => $prefix, 'as' => $as, 'namespace' => $namespace, 'middleware' => 'auth'],
            function () {
                Route::get('ajaxCard', 'ConfigController@ajaxCard')->name('ajaxCard');
                Route::get('{modelName?}', 'ConfigController@index')->name('index');
                Route::get('{modelName}/create', 'ConfigController@create')->name('create');
                Route::post('{modelName}', 'ConfigController@store')->name('store');
                Route::get('{modelName}/{modelID}/show', 'ConfigController@show')->name('show');
                Route::get('{modelName}/{modelID}/edit', 'ConfigController@edit')->name('edit');
                Route::put('{modelName}/{modelID}', 'ConfigController@update')->name('update');
                Route::delete('{modelName}/{modelID}', 'ConfigController@destroy')->name('destroy');
                Route::get('{modelName}/ajax-form', 'ConfigController@ajaxOnChange')->name('ajaxOnChange');
            }
        );
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

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @param  string  $resourceClass Nombre del recurso a recuperar
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $resourceClass = '')
    {
        $resource = $this->getResource($resourceClass)
            ->makePaginatedResources($request);

        return view('orm.list', compact('resource'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param  Request $request
     * @param  string  $resourceClass Nombre del recurso
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, string $resourceClass = '')
    {
        $resource = $this->getResource($resourceClass)
            ->resolveFormFields($request);

        return view('orm.create', compact('resource'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @param  string  $resourceClass Nombre del recurso
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $resourceClass = '')
    {
        $resource = $this->getResource($resourceClass);

        $this->authorize('create', $resource->model());
        $validated = $this->validate($request, $resource->getValidation($request));

        $resource->model()->create($validated);

        $nextRoute = $request->redirect_to === 'next' ? '.index' : '.create';

        return redirect()
            ->route($this->routeName . $nextRoute, [$resource->getName()])
            ->with('alert_message', $this->alertMessage('orm.msg_save_ok', $resource, $request));
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @param  string  $resourceClass Nombre del recurso
     * @param  string  $modelId       ID del recurso
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $resourceClass = '', string $modelId = '')
    {
        $resource = $this->getResource($resourceClass, $modelId)
            ->resolveDetailFields($request);

        return view('orm.show', compact('resource', 'modelId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request $request
     * @param  string  $resourceClass Nombre del recurso
     * @param  string  $modelId       ID del recurso
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $resourceClass = '', string $modelId = '')
    {
        $resource = $this->getResource($resourceClass, $modelId)
            ->resolveFormFields($request);

        return view('orm.edit', compact('resource', 'modelId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  string  $resourceClass Nombre del recurso
     * @param  string  $modelId       ID del recurso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $resourceClass = '', string $modelId = '')
    {
        $resource = $this->getResource($resourceClass, $modelId);

        $this->authorize('update', $resource->model());
        $this->validate($request, $resource->getValidation($request));

        $resource->update($request, $modelId);

        $nextRoute = $request->redirect_to === 'next' ? '.show' : '.edit';

        return redirect()
            ->route($this->routeName . $nextRoute, [$resource->getName(), $modelId])
            ->with('alert_message', $this->alertMessage('orm.msg_save_ok', $resource, $request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  string  $resourceClass Nombre del recurso
     * @param  string  $modelId       ID del recurso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $resourceClass = '', string $modelId = '')
    {
        $resource = $this->getResource($resourceClass, $modelId);

        $this->authorize('delete', $resource->model());

        $resource->model()->destroy($modelId);

        return redirect()
            ->route("{$this->routeName}.index", [$resource->getName()])
            ->with('alert_message', $this->alertMessage('orm.msg_delete_ok', $resource, $request));
    }

    /**
     * Recupera el recurso para ser usado en llamadas ajax
     *
     * @param  Request  $request
     * @param  string  $resourceClass Nombre del recurso
     * @return string
     */
    public function ajaxOnChange(Request $request, string $resourceClass = '')
    {
        return $this->getResource($resourceClass)
            ->getModelAjaxFormOptions($request);
    }

    protected function alertMessage(string $message, Resource $resource, Request $request): string
    {
        return trans($message, [
            'nombre_modelo' => $resource->getLabel(),
            'valor_modelo' => $resource->title($request),
        ]);
    }
}
