<table class="table listado table-hover mb-0">
    @foreach ($resource->getPaginatedResources() as $resource)
        @if ($loop->first)
            <thead class="thead-light">
                <tr>
                    @each('orm.components.listado.tabla_header', $resource['fields'], 'field')
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
                @include('orm.components.listado.tabla_item_buttons')
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
