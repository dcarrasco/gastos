<table class="table listado table-hover mb-0">
    @foreach ($resource->getPaginationResources() as $resource)
        @if ($loop->first)
            <thead class="thead-light">
                <tr>
                    @each('orm.listado_table_header', $resource['fields'], 'field')
                    <th class="text-center"></th>
                </tr>
            </thead>

            <tbody>
        @endif

        <tr>
            @foreach($resource['fields'] as $field)
                <td class="align-middle text-black-70">{{ $field->value() }}</td>
            @endforeach

            <td class="text-right text-nowrap">
                <a class="btn py-0 px-1 text-muted" href="{{ route($routeName.'.show', [$resource['resource']->getName(), $resource['resource']->model()->getKey()]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M17.56 17.66a8 8 0 0 1-11.32 0L1.3 12.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95zm-9.9-1.42a6 6 0 0 0 8.48 0L20.38 12l-4.24-4.24a6 6 0 0 0-8.48 0L3.4 12l4.25 4.24zM11.9 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                </a>
                <a class="btn py-0 px-1 text-muted" href="{{ route($routeName.'.edit', [$resource['resource']->getName(), $resource['resource']->model()->getKey()]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M6.3 12.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"/></svg>
                </a>
                <a class="btn py-0 px-1 text-muted" data-toggle="modal" data-target="#modalBorrar" data-url-form="{!! route($routeName.'.destroy', [$resource['resource']->getName(), $resource['resource']->model()->getKey()]) !!}"" id="delete-href">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8H3a1 1 0 1 1 0-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1z"/></svg>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
