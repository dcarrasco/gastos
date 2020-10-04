<form id="filtroReporte" method="GET" class="flex justify-center items-center pb-6">
    <div class="px-4">A&ntilde;o</div>
    <x-form-input name="anno" type="selectYear" :from-year="today()->year" to-year="2015" />
</form>

<script type="text/javascript">
    $('form#filtroReporte select').on('change', function() {
        $('form#filtroReporte').submit();
    });
</script>
