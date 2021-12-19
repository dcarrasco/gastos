@props(['cuentas', 'tiposMovimientos'])

<form method="GET" class="flex justify-center items-center pb-6" x-data="{}" x-ref="form">
    <div class="px-4">Cuenta</div>
    <x-form-input type="select" name="cuenta_id" :options="$cuentas" x-on:change="$refs.form.submit()" />

    <div class="ml-5 px-4">A&ntilde;o</div>
    <x-form-input type="selectYear" name="anno" x-on:change="$refs.form.submit()" />

    <div class="ml-5 px-4">Tipo Movimiento</div>
    <x-form-input type="select" name="tipo_movimiento_id" :options="$tiposMovimientos" x-on:change="$refs.form.submit()" />
</form>
