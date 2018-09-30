<?php

namespace App\Http\Controllers\Orm;

use Route;
use App\OrmModel\OrmField;
use Illuminate\Http\Request;
use App\OrmModel\Filters\PerPage;

trait OrmController
{
    protected $routeName = '';

    protected $menuModulo = [];

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
        view()->share('menuModulo', $this->makeMenuModuloURL($this->menuModulo));
        view()->share('routeName', $this->routeName);

        $resource = collect($this->menuModulo)->first();
        view()->share('moduloSelected',
            empty(Route::input('modelName')) ? (new $resource)->getName() : Route::input('modelName')
        );
    }

    protected function getResource($resourceName = '')
    {
        $resource = collect($this->menuModulo)
            ->first(function($resource) use ($resourceName) {
                return empty($resourceName) or (new $resource)->getName() === $resourceName;
            });

        return new $resource;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $resource = null)
    {
        $resource = $this->getResource($resource);
        $modelList = $resource->modelList($request);
        $paginationLinks = $resource->getPaginationLinks($request);
        $modelId = null;

        return view('orm.orm_listado',
            compact('resource', 'modelList', 'paginationLinks', 'modelId')
        );
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($resource = null)
    {
        $resource = $this->getResource($resource);
        $accionForm = trans('orm.title_add');
        $createOrEdit = 'create';
        $formURL = route($this->routeName.'.store', [$resource->getName()]);
        $buttonAction = trans('orm.button_create').' '.$resource->getLabel();
        $buttonActionContinue = trans('orm.button_create_continue');

        return view('orm.orm_editar',
            compact('resource', 'accionForm', 'createOrEdit', 'formURL', 'buttonAction', 'buttonActionContinue')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $resource = null)
    {
        $resource = $this->getResource($resource);
        $this->validate($request, $resource->getValidation($request));

        $resource->model()->create($request->all());

        $alertMessage = trans('orm.msg_save_ok', [
            'nombre_modelo' => $resource->getLabel(),
            'valor_modelo' => $resource->title($request),
        ]);
        $nextRoute = $request->redirect_to === 'next' ? '.index' : '.create';

        return redirect()->route($this->routeName.$nextRoute, [$resource->getName()])
                ->with('alert_message', $alertMessage);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show($resource = null, $modelId = null)
    {
        $resource = $this->getResource($resource)->findOrNew($modelId);

        return view('orm.orm_show', compact('resource', 'modelId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit($resource = null, $modelId = null)
    {
        $resource = $this->getResource($resource)->findOrNew($modelId);

        $accionForm = trans('orm.title_edit');
        $createOrEdit  = 'edit';
        $formURL = route($this->routeName.'.update', [$resource->getName(), $modelId]);
        $buttonAction = trans('orm.button_update').' '.$resource->getLabel();
        $buttonActionContinue = trans('orm.button_update_continue');

        return view(
            'orm.orm_editar',
            compact('resource', 'modelId', 'accionForm', 'createOrEdit', 'formURL', 'buttonAction', 'buttonActionContinue')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $resource = null, $modelId = null)
    {
        $resource = $this->getResource($resource)->findOrFail($modelId);
        $this->validate($request, $resource->getValidation($request));

        $resource->update($request, $modelId);

        $alertMessage = trans('orm.msg_save_ok', [
            'nombre_modelo' => $resource->getLabel(),
            'valor_modelo' => $resource->title($request),
        ]);
        $nextRoute = $request->redirect_to === 'next' ? '.show' : '.edit';

        return redirect()->route($this->routeName.$nextRoute, [$resource->getName(), $modelId])
                ->with('alert_message', $alertMessage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $resource = null, $modelId = null)
    {
        $resource = $this->getResource($resource)->findOrFail($modelId);
        $resource->model()->destroy($modelId);

        return redirect()
            ->route($this->routeName.'.index', [$resource->getName()])
            ->with('alert_message', trans('orm.msg_delete_ok', [
                'nombre_modelo' => $resource->getLabel(),
                'valor_modelo' => $resource->title($request),
            ]));
    }

    /**
     * Recupera el recurso para ser usado en llamadas ajax
     * @param  Request $request  [description]
     * @param  [type]  $resource [description]
     * @return [type]            [description]
     */
    public function ajaxOnChange(Request $request, $resource = null)
    {
        return $this->getResource($resource)
            ->getModelAjaxFormOptions($request);
    }
}
