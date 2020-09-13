<table class="w-full">
    @foreach ($resource->resourceList() as $resource)
        @if ($loop->first)
            <thead class="bg-gray-300">
                <tr>
                    @foreach($resource->getFields() as $field)
                        <th class="py-3 px-3 {{ $field->alignOnList() }}">
                            <small class="text-xs text-gray-500 uppercase font-bold">
                                {{ $field->getName() }}
                            </small>
                            {{ $field->sortingIcon() }}
                        </th>
                    @endforeach
                    <th></th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-300">
        @endif

        <tr class="">
            @foreach($resource->getFields() as $field)
                <td class="py-3 px-3 {{ $field->alignOnList() }}">
                    {{ $field->value() }}
                </td>
            @endforeach

            <td class="py-3 px-3 text-gray-600 text-right">
                <x-orm.list.table-item-buttons :resource=$resource />
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
