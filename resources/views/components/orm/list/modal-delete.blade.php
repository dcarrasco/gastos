<div x-show="openDeleteModal" style="display: none;">
    <div class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 text-base">
            <div class="fixed inset-0 transition-opacity" @click="openDeleteModal=false">
                <div class="absolute inset-0 bg-gray-800 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

            <div class="p-4 inline-block align-bottom bg-white rounded-lg border text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                <div class="flex justify-between py-2">
                    <h5 class="text-xl font-bold" id="exampleModalCenterTitle">
                        Borrar {{ $resource->getLabel() }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="openDeleteModal=false">
                        <span class="fa fa-close"></span>
                    </button>
                </div>

                <div x-html="deleteMessage" class="py-2">
                    {!! trans('orm.delete_confirm', [
                        'model' => $resource->getLabel(),
                        'item' => $resource->title()
                    ]) !!}
                </div>

                <div class="flex justify-end py-2">
                    <button type="button" class="bg-gray-200 hover:bg-gray-400 px-4 py-2 mx-2 rounded-md border border-gray-400 focus:outline-none focus:shadow-outline" @click="openDeleteModal=false">
                        {{ trans('orm.button_cancel') }}
                    </button>
                    <form method="POST" x-bind:action="urlDelete" action="{{ route($routeName.'.destroy', [$resource->getName(), 0]) }}" id="formDelete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 px-4 py-2 font-bold text-white border border-red-700 rounded-md focus:outline-none focus:shadow-outline" name="borrar" value="borrar">
                            <span class="fa fa-trash-o"></span>
                            {{ trans('orm.button_delete') }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
