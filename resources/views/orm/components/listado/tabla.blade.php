<table class="table listado table-hover mb-0">
    @foreach ($resource->getPaginatedResources() as $resource)
        @if ($loop->first)
            <thead class="thead-light">
                <tr>
                    @foreach($resource['fields'] as $field)
                        <th class="text-nowrap {{ $field->alignOnList() }}">
                            <h6 class="my-0">
                                <small class="text-muted text-uppercase font-weight-bold mr-1">
                                    {!! $field->getName() !!}
                                </small>
                                {!! $field->sortingIcon() !!}
                            </h6>
                        </th>
                    @endforeach
                    <th></th>
                </tr>
            </thead>

            <tbody>
        @endif

        <tr>
            @foreach($resource['fields'] as $field)
                <td class="align-middle text-black-70 {{ $field->alignOnList() }}">
                    {{ $field->value() }}
                </td>
            @endforeach

            <td class="text-right text-nowrap">
                @include('orm.components.listado.tabla_item_buttons')
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
