<?php

namespace App\Http\Controllers\Orm;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Model;

class OrmController extends Controller
{
    use OrmCard;
    use OrmControllerHelper;

    /**
     * Nombre de la ruta del controlador
     *
     * @var string
     */
    protected string $routeName = '';

    /**
     * Listado de modulos del controlador
     *
     * @var class-string[]
     */
    protected array $menuModulo = [];


    public function __construct(Request $request)
    {
        if (empty($this->routeName)) {
            throw new \Exception("El parametro routeName no esta definido");
        }

        $this->makeView($request);
    }

    public function index(Request $request, string $resourceClass = ''): View
    {
        $resource = $this->getResource($resourceClass)
            ->makePaginatedResources($request);

        return view('orm.list', compact('resource'));
    }

    public function create(Request $request, string $resourceClass = ''): View
    {
        $resource = $this->getResource($resourceClass)
            ->resolveFormFields($request);

        return view('orm.create', compact('resource'));
    }

    public function store(Request $request, string $resourceClass = ''): RedirectResponse
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

    public function show(Request $request, string $resourceClass = '', string $modelId = ''): View
    {
        $resource = $this->getResource($resourceClass, $modelId)
            ->resolveDetailFields($request);

        return view('orm.show', compact('resource'));
    }

    public function edit(Request $request, string $resourceClass = '', string $modelId = ''): View
    {
        $resource = $this->getResource($resourceClass, $modelId)
            ->resolveFormFields($request);

        return view('orm.edit', compact('resource'));
    }

    public function update(Request $request, string $resourceClass = '', string $modelId = ''): RedirectResponse
    {
        $resource = $this->getResource($resourceClass, $modelId);

        $this->authorize('update', $resource->model());
        $this->validate($request, $resource->getValidation($request));

        $resource->update($request);

        $nextRoute = $request->redirect_to === 'next' ? '.show' : '.edit';

        return redirect()
            ->route($this->routeName . $nextRoute, $resource->getRouteControllerId())
            ->with('alert_message', $this->alertMessage('orm.msg_save_ok', $resource, $request));
    }

    public function destroy(Request $request, string $resourceClass = '', string $modelId = ''): RedirectResponse
    {
        $resource = $this->getResource($resourceClass, $modelId);

        $this->authorize('delete', $resource->model());

        $resource->model()->destroy($modelId);

        return redirect()
            ->route("{$this->routeName}.index", [$resource->getName()])
            ->with('alert_message', $this->alertMessage('orm.msg_delete_ok', $resource, $request));
    }

    /**
     * Recupera opciones select cuando un campo cambia
     *
     * @param Request $request
     * @param string $resourceClass
     * @return string
     */
    public function ajaxOnChange(Request $request, string $resourceClass = ''): string
    {
        return $this->getResource($resourceClass)
            ->getModelAjaxFormOptions($request);
    }
}
