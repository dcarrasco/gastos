<form method="GET" class="flex justify-center items-center pb-6" x-data="{}" x-ref="form">
    <div class="px-4">Cuenta</div>
    <x-form-input name="cuenta_id" type="select" :options=$cuentas x-on:change="$refs.form.submit()" />

    <label class="ml-5 mr-2">A&ntilde;o</label>
    <x-form-input name="anno" type="selectYear" :default="today()->year" :from-year="today()->year" to-year="2015" x-on:change="$refs.form.submit()" />
</form>
