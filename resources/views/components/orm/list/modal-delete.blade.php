    <div x-show="openDeleteModal" class="bg-white">
        <div class="">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h5 class="modal-title" id="exampleModalCenterTitle">
                Borrar {{ $resource->getLabel() }}
            </h5>
            <div x-html="deleteMessage">
                {!! trans('orm.delete_confirm', [
                    'model' => $resource->getLabel(),
                    'item' => $resource->title()
                ]) !!}
            </div>
        </div>

        <div class="flex">
            <button type="button" class="bg-gray-200 hover:bg-gray-400 px-4 py-2 rounded-md border border-gray-400" @click="openDeleteModal=false">
                {{ trans('orm.button_cancel') }}
            </button>
            <form method="POST" action="{{ route($routeName.'.destroy', [$resource->getName(), 0]) }}" id="formDelete">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 px-4 py-2 font-bold text-white border border-red-700 rounded-md" name="borrar" value="borrar">
                    <span class="fa fa-trash-o"></span>
                    {{ trans('orm.button_delete') }}
                </button>
            </form>
        </div>
    </div>
</div>
