<div class="flex justify-center items-center pb-6">
    <label class="mr-2">Cuenta</label>
    <x-form-input type="select" name="cuenta_id" :options="$formCuenta" />

    <label class="ml-5 mr-2">A&ntilde;o</label>
    <x-form-input type="selectYear" name="anno" />

    <label class="ml-5 mr-2">Mes</label>
    <x-form-input type="selectMonth" name="mes" />

    <label class="ml-5 mr-2">Parser</label>
    <x-form-input type="select" name="parser" :options="$formParser" />
</div>

<div class="grid grid-cols-10">
    <div class="col-start-2">Datos</div>
    <x-form-input type="textarea" name="datos" class="col-start-2 col-span-8" cols="50" rows="10" />
    <div class="col-start-2 col-span-8 flex justify-end py-4">
        <x-button type="submit">
            Procesar
        </x-button>
    </div>
</div>
