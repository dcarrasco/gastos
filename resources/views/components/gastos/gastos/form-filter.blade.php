<div class="flex justify-between pb-6">
    <form method="GET" class="col-span-3 flex justify-center items-center" x-data="{}" x-ref="form">
        <label class="mr-2">Cuenta</label>
        <x-form-input name="cuenta_id" type="select" :options=$selectCuentas x-on:change="$refs.form.submit()"/>

        <label class="ml-5 mr-2">A&ntilde;o</label>
        <x-form-input name="anno" type="selectYear" :default="today()->year" :from-year="today()->year" to-year="2015" x-on:change="$refs.form.submit() "/>

        <label class="ml-5 mr-2">Mes</label>
        <x-form-input name="mes" type="selectMonth" :default="today()->month" x-on:change="$refs.form.submit()" />
    </form>

    <div class="col-span-1">
        <x-button color="secondary" name="recalcula" value="recaulcula">
            Recalcula saldos
        </x-button>
    </div>
</div>
