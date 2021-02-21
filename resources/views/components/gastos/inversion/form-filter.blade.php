@props(['cuentas'])

<form method="GET" class="flex justify-center items-center pb-6" x-data x-ref="form">
    <div class="px-4">Cuenta</div>
    <x-form-input type="select" name="cuenta_id" :options="$cuentas" @change="$refs.form.submit()" />

    <label class="ml-5 mr-2">A&ntilde;o</label>
    <x-form-input type="selectYear" name="anno" :value="today()->year" @change="$refs.form.submit()" />
</form>
