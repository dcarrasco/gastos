<table class="table listado table-hover mb-0">
    @foreach ($resource->resourceList() as $resource)
        @if ($loop->first)
            <thead class="thead-light">
                <tr>
                    @foreach($resource->getFields() as $field)
                        <th class="text-nowrap {{ $field->alignOnList() }}">
                            <h6 class="my-0">
                                <small class="text-muted text-uppercase font-weight-bold mr-1">
                                    {{ $field->getName() }}
                                </small>
                                {{ $field->sortingIcon() }}
                            </h6>
                        </th>
                    @endforeach
                    <th></th>
                </tr>
            </thead>

            <tbody>
        @endif

        <tr>
            @foreach($resource->getFields() as $field)
                <td class="align-middle text-black-70 {{ $field->alignOnList() }}">
                    {{ $field->value() }}
                </td>
            @endforeach

            <td class="text-right text-nowrap">
                <x-orm.list.table-item-buttons :resource=$resource />
            </td>
        </tr>
    @endforeach
    </tbody>
</table>