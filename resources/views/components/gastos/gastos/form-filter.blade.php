@props(['selectCuentas'])

<div class="flex justify-between pb-6">
    <form method="GET" class="col-span-3 flex justify-center items-center" x-data x-ref="form">
        <label class="mr-2">Cuenta</label>
        <x-form-input type="select" name="cuenta_id" :options="$selectCuentas" @change="$refs.form.submit()" />

        <label class="ml-5 mr-2">A&ntilde;o</label>
        <x-form-input type="selectYear" name="anno" :value="today()->year" @change="$refs.form.submit()" />

        <label class="ml-5 mr-2">Mes</label>
        <x-form-input type="selectMonth" name="mes" :value="today()->month" @change="$refs.form.submit()" />
    </form>

    <div class="col-span-1">
        <x-button color="secondary" name="recalcula" value="recalcula">
            Recalcula saldos
        </x-button>
    </div>
</div>
