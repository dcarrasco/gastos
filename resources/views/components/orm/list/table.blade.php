@props(['resource'])

<div x-data="{openDeleteModal: false, deleteMessage: '', urlDelete: ''}">
    <table class="w-full">
        @foreach ($resource->resourceList() as $resourceItem)
            @if ($loop->first)
                <thead class="bg-gray-200 border-b-2">
                    <tr>
                        @foreach($resourceItem->getFields() as $field)
                            <th class="py-3 px-3 text-gray-600 text-xs uppercase font-bold {{ $field->alignOnList() }}">
                                {{ $field->getName() }}
                                {{ $field->sortingIcon() }}
                            </th>
                        @endforeach
                        <th></th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
            @endif

            <tr class="hover:{{ themeColor('tr_hover') }}">
                @foreach($resourceItem->getFields() as $field)
                    <td class="py-3 px-3 {{ $field->alignOnList() }}">
                        {{ $field->getFormattedValue() }}
                    </td>
                @endforeach

                <td class="py-3 px-3 text-gray-500 text-right whitespace-no-wrap">
                    <x-orm.list.table-item-buttons :resource="$resourceItem" />
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <x-paginator.links :resource="$resource" />

    <x-orm.list.modal-delete :resource="$resource" />
</div>
