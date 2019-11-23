<?php

namespace App\Http\Controllers\Orm;

use Route;
use Illuminate\Http\Request;
use App\OrmModel\Filters\PerPage;
use App\Http\Controllers\Controller;

class OrmController extends Controller
{
    protected $routeName = '';

    protected $menuModulo = [];


    public function __construct()
    {
        $this->makeView();
    }

    /**
     * Genera menu
     *
     * @return array
     */
    public function makeMenuModuloURL()
    {
        $routeName = $this->routeName;

        return collect($this->menuModulo)->map(function ($resourceName) use ($routeName) {
            $resource = new $resourceName;

            return [
                'resource' => $resource->getName(),
                'nombre' => $resource->getLabelPlural(),
                'url'    => route($routeName.'.index', $resource->getName()),
                'icono'  => $resource->icono,
            ];
        });
    }


    public function makeView()
    {
        view()->share('perPageFilter', new PerPage);
        view()->share('menuModulo', $this->makeMenuModuloURL());
        view()->share('routeName', $this->routeName);

        $resource = collect($this->menuModulo)->first();

        view()->share('moduloSelected',
            empty(Route::input('modelName')) ? (new $resource)->getName() : Route::input('modelName')
        );
    }

    /**
     * Devuelve un recurso
     *
     * @param  string  $resourceName Nombre del recurso a recuperar
     * @param  Request $request
     * @return Resource
     */
    protected function getResource($resourceName = '')
    {
        $resource = collect($this->menuModulo)->first(function($resource) use ($resourceName) {
            return (new $resource)->getName() === $resourceName;
        });

        $resource = $resource ?: collect($this->menuModulo)->first();

        return new $resource;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @param  string  $resourceClass Nombre del recurso a recuperar
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $resourceClass = null)
    {
        $resource = $this->getResource($resourceClass)->makePaginator($request);
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
    public function create(Request $request, $resourceClass = null)
    {
        $resource = $this->getResource($resourceClass);
        $fields = $resource->formFields($request);

        return view('orm.create', compact('resource', 'fields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @param  string  $resourceClass Nombre del recurso
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $resourceClass = null)
    {
        $resource = $this->getResource($resourceClass);
        $this->validate($request, $resource->getValidation($request));

        $resource->model()->create($request->all());

        $nextRoute = $request->redirect_to === 'next' ? '.index' : '.create';
        $alertMessage = trans('orm.msg_save_ok', [
            'nombre_modelo' => $resource->getLabel(),
            'valor_modelo' => $resource->title(),
        ]);

        return redirect()
            ->route($this->routeName.$nextRoute, [$resource->getName()])
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
    public function show(Request $request, $resourceClass = null, $modelId = null)
    {
        $resource = $this->getResource($resourceClass)->findOrNew($modelId);
        $fields = $resource->detailFields($request);

        return view('orm.show', compact('resource', 'modelId', 'fields'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request $request
     * @param  string  $resourceClass Nombre del recurso
     * @param  string  $modelId       ID del recurso
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $resourceClass = null, $modelId = null)
    {
        $resource = $this->getResource($resourceClass)->findOrNew($modelId);
        $fields = $resource->formFields($request);

        return view('orm.edit', compact('resource', 'modelId', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  string  $resourceClass Nombre del recurso
     * @param  string  $modelId       ID del recurso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $resourceClass = null, $modelId = null)
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
            ->route($this->routeName.$nextRoute, [$resource->getName(), $modelId])
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
    public function destroy(Request $request, $resourceClass = null, $modelId = null)
    {
        $resource = $this->getResource($resourceClass)->findOrFail($modelId);
        $resource->model()->destroy($modelId);

        $alertMessage = trans('orm.msg_delete_ok', [
            'nombre_modelo' => $resource->getLabel(),
            'valor_modelo' => $resource->title($request)
        ]);

        return redirect()
            ->route($this->routeName.'.index', [$resource->getName()])
            ->with('alert_message', $alertMessage);
    }

    /**
     * Recupera el recurso para ser usado en llamadas ajax
     *
     * @param  Request  $request
     * @param  string  $resourceClass Nombre del recurso
     * @return string
     */
    public function ajaxOnChange(Request $request, $resourceClass = null)
    {
        return $this->getResource($resourceClass)
            ->getModelAjaxFormOptions($request);
    }

    /**
     * Recupera el recurso para ser usado en llamadas ajax
     *
     * @param  Request  $request
     * @param  string  $resourceClass Nombre del recurso
     * @return
     */
    public function ajaxCard(Request $request, $resourceClass = null)
    {
        return collect($this->getResource($resourceClass)->cards($request))
            ->first(function($card) use ($request) {
                return $card->uriKey() === $request->input('uri-key');
            })->calculate($request);
    }
}
