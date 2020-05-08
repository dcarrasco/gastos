<form id="filtroReporte" method="GET" class="form-inline justify-content-center">
    <label class="ml-5 mr-2">A&ntilde;o</label>
    <x-form-input name="anno" type="selectYear" :from-year="today()->year" to-year="2015" />
</form>

<script type="text/javascript">
    $('form#filtroReporte select').on('change', function() {
        $('form#filtroReporte').submit();
    });
</script>
