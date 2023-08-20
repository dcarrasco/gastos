@if (config('invfija.use_breadcrumbs'))
    <div class="pt-0 mb-4">
        {{ auth()->user()->getBreadcrumbs($resource, $accion) }}
    </div>
@endif
