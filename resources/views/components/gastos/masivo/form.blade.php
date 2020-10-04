<div class="flex justify-center items-center pb-6">
    <label class="mr-2">Cuenta</label>
    <x-form-input name="cuenta_id" type="select" :options=$formCuenta />

    <label class="ml-5 mr-2">A&ntilde;o</label>
    <x-form-input name="anno" type="selectYear" :default="today()->year" :from-year="today()->year" to-year="2015" />

    <label class="ml-5 mr-2">Mes</label>
    <x-form-input name="mes" type="selectMonth" :default="today()->month" />
</div>

<div class="grid grid-cols-10">
    <div class="col-start-2">Datos</div>
    <textarea name="datos" class="col-start-2 col-span-8 rounded-md px-4 py-2 outline-none focus:shadow-outline" cols="50" rows="10">{{ request('datos') }}</textarea>
    <div class="col-start-2 col-span-8 flex justify-end py-4">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-md outline-none">Procesar</button>
    </div>
</div>
