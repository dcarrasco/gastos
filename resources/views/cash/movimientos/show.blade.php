<x-layout.app>

    <div class="text-xl">
        Cuenta:
        {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
    </div>

    <form method="POST" class="py-8">
        @csrf

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
            <div>
                <a href="{{ route('cashConfig.create', ['cuenta']) }}" class="px-4 text-small hover:text-blue-500" color="secondary">
                    nueva cuenta
                </a>
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

        @can('create', 'App\Models\Cash\Movimiento')
        <div class="grid grid-cols-4 px-5 py-5 text-gray-600">
            <div class="col-span-3 flex justify-end">
                <x-button type="submit">Agregar</x-button>
                &nbsp;&nbsp;&nbsp;
                <x-button type="link" color="danger" link="{{ route('cashMovimientos.index', ['cuenta' => $cuenta]) }}">Cancelar</x-button>
            </div>
        </div>
        @endcan
    </form>

</x-layout.app>
