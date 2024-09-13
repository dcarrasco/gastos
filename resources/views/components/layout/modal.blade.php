@props(['title', 'buttons'])

<div x-show="openDeleteModal" style="display: none;">
    <div class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 text-base">
            <div class="fixed inset-0 transition-opacity" x-on:click="openDeleteModal=false">
                <div class="absolute inset-0 bg-gray-800 opacity-50"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

            <div
                x-show="openDeleteModal"
                x-transition
                class="p-4 inline-block align-bottom bg-white rounded-lg border text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog"
                aria-modal="true"
                aria-labelledby="modal-headline"
                style="display: none;"
            >
                <div class="flex justify-between pt-2 pb-5">
                    {{ $title }}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" x-on:click="openDeleteModal=false">
                        <span class="fa fa-close"></span>
                    </button>
                </div>

                <div class="py-2">
                    {{ $slot }}
                </div>

                <div class="flex justify-end py-2">
                    {{ $buttons }}
                </div>
            </div>
        </div>
    </div>
</div>
