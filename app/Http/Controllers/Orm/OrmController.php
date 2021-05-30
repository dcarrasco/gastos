<?php

namespace App\Http\Controllers\Orm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrmController extends Controller
{
    use OrmCard;
    use OrmControllerHelper;

    protected $routeName = '';

    protected $menuModulo = [];


    public function __construct(Request $request)
    {
        if (empty($this->routeName)) {
            throw new \Exception("El parametro routeName no esta definido");
        }

        $this->menuModulo = collect($this->menuModulo)
            ->map(fn($resource) => new $resource());

        $this->makeView($request);
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

        return view('orm.show', compact('resource'));
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

        return view('orm.edit', compact('resource'));
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
}
