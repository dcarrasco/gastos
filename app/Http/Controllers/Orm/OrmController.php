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
    public function makeMenuModuloURL()
    {
        return $this->menuModulo->map(function ($resource) {
            return [
                'nombre' => $resource->getLabelPlural(),
                'url' => route("{$this->routeName}.index", $resource->getName()),
            ];
        });
    }


    public function makeView()
    {
        $resource = $this->menuModulo->first();

        view()->share('perPageFilter', new PerPage());
        view()->share('menuModulo', $this->makeMenuModuloURL());
        view()->share('routeName', $this->routeName);
        view()->share('moduloSelected', Route::input('modelName') ?? $resource->getName());
    }

    /**
     * Devuelve un recurso
     *
     * @param  string  $resourceName Nombre del recurso a recuperar
     * @param  Request $request
     * @return Resource
     */
    protected function getResource(string $resourceName = ''): Resource
    {
        return $this->menuModulo->first(function ($resource) use ($resourceName) {
            return $resource->getName() === $resourceName;
        })
        ?? $this->menuModulo->first();
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
            ->map->cards($request)
            ->flatten()
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
        $resource = $this->getResource($resourceClass)->makePaginatedResources($request);
        $cards = $resource->renderCards($request);

        return view('orm.list', compact('resource', 'cards'));
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
            ->formFields($request);

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
        $validated = $this->validate($request, $resource->getValidation($request));

        $resource->model()->create($validated);

        $nextRoute = $request->redirect_to === 'next' ? '.index' : '.create';
        $alertMessage = trans('orm.msg_save_ok', [
            'nombre_modelo' => $resource->getLabel(),
            'valor_modelo' => $resource->title(),
        ]);

        return redirect()
            ->route($this->routeName . $nextRoute, [$resource->getName()])
            ->with('alert_message', $alertMessage);
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
        $resource = $this->getResource($resourceClass)->findOrNew($modelId)
            ->detailFields($request);

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
        $resource = $this->getResource($resourceClass)->findOrNew($modelId)
            ->formFields($request);

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
        $resource = $this->getResource($resourceClass)->findOrFail($modelId);
        $this->validate($request, $resource->getValidation($request));

        $resource->update($request, $modelId);

        $nextRoute = $request->redirect_to === 'next' ? '.show' : '.edit';
        $alertMessage = trans('orm.msg_save_ok', [
            'nombre_modelo' => $resource->getLabel(),
            'valor_modelo' => $resource->title($request),
        ]);

        return redirect()
            ->route($this->routeName . $nextRoute, [$resource->getName(), $modelId])
            ->with('alert_message', $alertMessage);
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
        $resource = $this->getResource($resourceClass)->findOrFail($modelId);
        $resource->model()->destroy($modelId);

        $alertMessage = trans('orm.msg_delete_ok', [
            'nombre_modelo' => $resource->getLabel(),
            'valor_modelo' => $resource->title($request)
        ]);

        return redirect()
            ->route("{$this->routeName}.index", [$resource->getName()])
            ->with('alert_message', $alertMessage);
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
}
