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

        return collect($this->menuModulo)->map(function ($resource) use ($routeName) {
            return [
                'resource' => $resource->getName(),
                'nombre' => $resource->getLabel(),
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
        view()->share(
            'moduloSelected',
            empty(Route::input('modelName'))
            ? collect(array_keys($this->menuModulo))->first()
            : Route::input('modelName')
        );
    }

    protected function getResource($resourceName = '')
    {
        $resourceName = empty($resourceName) ? collect($this->menuModulo)->first()->getName() : $resourceName;

        return collect($this->menuModulo)->first(function($resource) use ($resourceName) {
            return $resource->getName() === $resourceName;
        });
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $resource = null)
    {
        $resource = $this->getResource($resource);
        $modelList = $resource->getModelList($request);
        $paginationLinks = $resource->getPaginationLinks($request);

        return view('orm.orm_listado', compact('resource', 'modelList', 'paginationLinks'));
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

        return view(
            'orm.orm_editar',
            compact('resource', 'accionForm', 'createOrEdit', 'formURL')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $modelName = null)
    {
        $modelObject = $this->getResource($modelName);
        $this->validate($request, $modelObject->getValidation($request));

        $modelObject = $modelObject->create($request->all());

        return redirect()
            ->route($this->routeName.'.index', [$modelName])
            ->with('alert_message', trans('orm.msg_save_ok', [
                'nombre_modelo'=> $modelObject->getLabel(),
                'valor_modelo' => $modelObject->title()
            ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit($resource = null, $modelId = null)
    {
        $resource = $this->getResource($resource);
        $resource = $resource->injectModel($resource->getModelObject()->findOrNew($modelId));

        $accionForm    = trans('orm.title_edit');
        $createOrEdit  = 'edit';
        $formURL       = route($this->routeName.'.update', [$resource->getName(), $modelId]);

        return view(
            'orm.orm_editar',
            compact('resource', 'modelId', 'accionForm', 'createOrEdit', 'formURL')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $modelName = null, $modelID = null)
    {
        $fullModelName = $this->modelNameSpace.ucfirst($modelName);
        $modelObject = $fullModelName::findOrFail($modelID);
        $this->validate($request, $modelObject->getValidation());

        // actualiza el objeto
        $modelObject->update($request->all());

        // actualiza las tablas relacionadas
        collect($modelObject->fields())->filter(function($elem) {
            // filtra los campos de TIPO_HAS_MANY
            return get_class($elem) === 'App\OrmModel\OrmField\HasManyField';
        })->each(function ($elem, $field) use ($modelObject, $request) {
            // Sincroniza la tabla relacionada
            $modelObject->$field()->sync($request->input($field, []));
        });

        return redirect()
            ->route($this->routeName.'.index', [$modelName])
            ->with('alert_message', trans('orm.msg_save_ok', [
                'nombre_modelo' => $modelObject->getLabel(),
                'valor_modelo' => $modelObject->title(),
            ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $modelName = null, $modelID = null)
    {
        $fullModelName = $this->modelNameSpace.ucfirst($modelName);
        $modelObject = $fullModelName::find($modelID);
        $fullModelName::destroy($modelID);

        return redirect()
            ->route($this->routeName.'.index', [$modelName])
            ->with('alert_message', trans('orm.msg_delete_ok', [
                'nombre_modelo' => $modelObject->getLabel(),
                'valor_modelo' => $modelObject->title(),
            ]));
    }

    public function ajaxOnChange(Request $request, $modelName = null)
    {
        $fullModelName = $this->modelNameSpace.ucfirst($modelName);
        $modelObject = new $fullModelName;

        return $modelObject->getModelAjaxFormOptions(request()->input());
    }
}
