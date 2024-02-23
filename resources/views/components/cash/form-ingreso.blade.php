@props(['cuenta' => [], 'selectCuentas' => [], 'tiposCargo' => [], 'movimiento' => null])

<div x-data="{ open: {{ (is_null($movimiento) and $errors->count() == 0) ? 'false' : 'true' }} }">

    <div class="flex justify-end py-3" x-show="!open">
        <x-button @click="open = ! open">Ingresar</x-button>
    </div>

    <div x-show="open">
        <form method="POST">
            @csrf
            @if (! is_null($movimiento)):
                @method('PUT')
            @endif
            <input type="hidden" name="cuenta_id" value="{{ $cuenta->id }}" />

            <div class="grid grid-cols-4 px-5 py-2 text-gray-600">
                <div class="col-span-1"> Fecha </div>
                <div class="col-span-2">
                    <x-form-input name="fecha" type="date" class="w-full" value="{{ $movimiento?->fecha->format('Y-m-d') }}"/>
                </div>
            </div>

            <div class="grid grid-cols-4 px-5 py-2 text-gray-600">
                <div class="col-span-1"> Numero </div>
                <div class="col-span-2">
                    <x-form-input name="numero" class="w-full" value="{{ $movimiento?->numero }}"/>
                </div>
            </div>

            <div class="grid grid-cols-4 px-5 py-2 text-gray-600">
                <div class="col-span-1"> Descripción </div>
                <div class="col-span-2">
                    <x-form-input name="descripcion" class="w-full" value="{{ $movimiento?->descripcion }}"/>
                </div>
            </div>

            <div class="grid grid-cols-4 px-5 py-2 text-gray-600">
                <div class="col-span-1"> Cuenta contrapartida </div>
                <div class="col-span-2">
                    <x-form-input
                        name="contracuenta_id"
                        type="select"
                        :options="$selectCuentas"
                        placeholder="--"
                        class="w-full"
                        value="{{ $movimiento?->contracuenta_id }}"
                    />
                </div>
            </div>

            <div class="grid grid-cols-4 px-5 py-2 text-gray-600">
                <div class="col-span-1"> Tipo Cargo </div>
                <div class="col-span-2">
                    <x-form-input
                        name="tipo_cargo"
                        type="select"
                        :options="$tiposCargo"
                        placeholder="--"
                        class="w-full"
                        value="{{ $movimiento?->tipo_cargo }}"
                    />
                </div>
            </div>

            <div class="grid grid-cols-4 px-5 py-2 text-gray-600">
                <div class="col-span-1"> Monto </div>
                <div class="col-span-2">
                    <x-form-input name="monto" class="w-full" value="{{ $movimiento?->monto }}"/>
                </div>
            </div>

            <div class="grid grid-cols-4 px-5 py-5 text-gray-600">
                <div class="col-span-3 flex justify-end">
                    <x-button type="submit">Agregar</x-button>
                    &nbsp;&nbsp;&nbsp;
                    <x-button @click="open = ! open" color="danger">Cancelar</x-button>
                </div>
            </div>
        </form>
    </div>

</div>
