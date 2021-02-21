<form method="GET" class="flex justify-center items-center pb-6" x-data="{}" x-ref="form">
    <div class="px-4">A&ntilde;o</div>
    <x-form-input type="selectYear" name="anno" :value="today()->year" x-on:change="$refs.form.submit()" />
</form>
