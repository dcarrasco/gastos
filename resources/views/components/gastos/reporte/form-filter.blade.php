<form method="GET" class="flex justify-center items-center pb-6" x-data="{}" x-ref="form">
    <div class="px-4">Cuenta</div>
    <x-form-input name="cuenta_id" type="select" :options=$cuentas x-on:change="$refs.form.submit()" />

    <div class="ml-5 px-4">A&ntilde;o</div>
    <x-form-input name="anno" :value="today()->year" type="selectYear" x-on:change="$refs.form.submit()" />

    <div class="ml-5 px-4">Tipo Movimiento</div>
    <x-form-input name="tipo_movimiento_id" type="select" :options=$tiposMovimientos x-on:change="$refs.form.submit()" />
</form>
