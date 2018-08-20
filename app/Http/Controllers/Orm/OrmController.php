<?php

namespace App\Http\Controllers\Orm;

use Route;
use App\OrmModel\OrmField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait OrmController
{
    protected $routeName = '';

    protected $menuModulo = [];

    public function makeMenuModuloURL()
    {
        $routeName = $this->routeName;

        return collect($this->menuModulo)->map(function ($elem, $key) use ($routeName) {
            return [
                'nombre' => $elem['nombre'],
                'url'    => route($routeName.'.index', [$key]),
                'icono'  => $elem['icono'],
            ];
        });
    }


    public function makeView()
    {
        view()->share('menuModulo', $this->makeMenuModuloURL($this->menuModulo));
        view()->share('routeName', $this->routeName);
        view()->share(
            'moduloSelected',
            empty(Route::input('modelName'))
            ? collect(array_keys($this->menuModulo))->first()
            : Route::input('modelName')
        );
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $modelName = null)
    {
        $modelName = empty($modelName) ? collect(array_keys($this->menuModulo))->first() : $modelName;
        $fullModelName = $this->modelNameSpace.ucfirst($modelName);
        $modelObject = new $fullModelName;
        $modelCollection = $modelObject->modelOrderBy()->filtroOrm(request('filtro'))->paginate();
        $paginationLinks = $modelCollection->appends(request()->only('filtro', 'orderby'))->links();

        return view('orm.orm_listado', compact('modelObject', 'modelCollection', 'modelName', 'paginationLinks'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($modelName = null)
    {
        $fullModelName = $this->modelNameSpace.ucfirst($modelName);
        $modelObject = new $fullModelName;

        $accionForm    = trans('orm.title_add');
        $createOrEdit  = 'create';
        $formURL       = route($this->routeName.'.store', [$modelName]);

        return view(
            'orm.orm_editar',
            compact('modelObject', 'fullModelName', 'modelName', 'accionForm', 'createOrEdit', 'formURL')
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
        $fullModelName = $this->modelNameSpace.ucfirst($modelName);
        $modelObject = new $fullModelName;
        $this->validate($request, $modelObject->getValidation());
        $modelObject = $fullModelName::create($request->all());

        return redirect()
            ->route($this->routeName.'.index', [$modelName])
            ->with(
                'alert_message',
                trans('orm.msg_save_ok', ['nombre_modelo'=>ucfirst($modelName), 'valor_modelo'=>(string) $modelObject])
            );
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
    public function edit($modelName = null, $modelID = null)
    {
        $fullModelName = $this->modelNameSpace.ucfirst($modelName);
        $modelObject = $fullModelName::findMultiKey($modelID);

        $accionForm    = trans('orm.title_edit');
        $createOrEdit  = 'edit';
        $formURL       = route($this->routeName.'.update', [$modelName, $modelID]);

        return view(
            'orm.orm_editar',
            compact('modelObject', 'modelID', 'fullModelName', 'modelName', 'accionForm', 'createOrEdit', 'formURL')
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
        $modelObject = $fullModelName::findMultiKey($modelID);
        $this->validate($request, $modelObject->getValidation());

        // actualiza el objeto
        $modelObject->updateMultiKey($request->all());

        // actualiza las tablas relacionadas
        $modelObject->getModelFields()->filter(function ($elem, $field) use ($modelObject) {
            // filtra los campos de TIPO_HAS_MANY
            return ($modelObject->getFieldType($field) === OrmField::TIPO_HAS_MANY);
        })->each(function ($elem, $field) use ($modelObject, $request) {
            // Sincroniza la tabla relacionada
            $modelObject->$field()->sync($request->input($field, []));
        });

        return redirect()
            ->route($this->routeName.'.index', [$modelName])
            ->with('alert_message', trans('orm.msg_save_ok', [
                'nombre_modelo' => $modelObject->modelLabel,
                'valor_modelo' => (string) $modelObject
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
                'nombre_modelo' => $modelObject->modelLabel,
                'valor_modelo' => (string) $modelObject
            ]));
    }

    public function ajaxOnChange(Request $request, $modelName = null)
    {
        $fullModelName = $this->modelNameSpace.ucfirst($modelName);
        $modelObject = new $fullModelName;

        return $modelObject->getModelAjaxFormOptions(request()->input());
    }
}
