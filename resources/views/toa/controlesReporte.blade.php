<div class="content-module-main">
@if ($control)
    <?php $tot_col = []; $count_col = []; ?>
    <table class="table table-bordered table-hover table-condensed reporte">

    @foreach ($control as $idControl => $datos)

        @if ($loop->iteration == 1)
            <!-- ENCABEZADO TABLA REPORTE -->
            <thead>
            <tr class="active">
                <th></th>

                {{-- Loop para cada uno de los campos de encabezado de la linea --}}
                @foreach ($controlCampos as $idCampo => $dataCampo)
                    <th class="{{ array_get($dataCampo, 'class') }}">{{ $dataCampo['label'] }}</th>
                @endforeach

                {{-- Loop para cada uno de los dias del reporte --}}
                @foreach ($datos['actuaciones'] as $dia_act => $cant_act)
                    <th class="text-center">
                        {{ dia_semana(date('w', strtotime(request('mes').((strlen($dia_act) === 1) ? '0'.$dia_act : $dia_act)))) }}<br>
                        {{ $dia_act }}
                        <?php $tot_col[$dia_act] = 0; $count_col[$dia_act] = 0; ?>
                    </th>
                @endforeach
                <?php $count_col['total'] = 0; ?>
                <th class="text-center">Tot Mes</th>
            </tr>
            </thead>

            <!-- CUERPO TABLA REPORTE -->
            <tbody>
        @endif

        <tr>
            <td class="text-muted">{{ $loop->iteration }}</td>

            {{-- Loop para cada uno de los campos de encabezado de la linea --}}
            @foreach ($controlCampos as $idCampo => $dataCampo)
                <td class="{{ array_get($dataCampo, 'class') }}" style="white-space: nowrap;">{{ $datos[$idCampo] }}</th>
            @endforeach

            {{-- Loop para cada uno de los dias del reporte --}}
            <?php $tot_lin = 0; ?>
            @foreach ($datos['actuaciones'] as $dia_act => $cant_act)
                @if ($cant_act)
                    <td class="text-center info">
                    <a href="">{{ fmtCantidad($cant_act) }}</a>
                    {{-- anchor($url_detalle_dia.'/'.$anomes.$dia_act.'/'.$anomes.$dia_act.'/'.$id_tecnico, fmtCantidad($cant_act)) --}}</td>
                @else
                    <td></td>
                @endif
                <?php $tot_lin += $cant_act; $tot_col[$dia_act] += $cant_act; $count_col[$dia_act] += $cant_act ? 1 : 0; ?>
            @endforeach

            <th class="text-center">{{ fmtCantidad($tot_lin) }}</th>
            <?php $count_col['total'] += $tot_lin ? 1 : 0; ?>
        </tr>
    @endforeach
    </tbody>

    <!-- PIE TABLA REPORTE -->
    <tfoot>
        <tr class="active">
            <th></th>
            <th></th>
            <th></th>
            <?php $tot_lin = 0; ?>
            @foreach ($tot_col as $dia_act => $total)
                <th class="text-center">{{ fmtCantidad($total) }}<?php $tot_lin += $total ?></th>
            @endforeach
            <th class="text-center">{{ fmtCantidad($tot_lin) }}</th>
        </tr>
        <tr class="active">
            <th></th>
            <th></th>
            <th></th>
            <?php $tot_lin = 0; ?>
            @foreach ($count_col as $dia_act => $count)
                <?php $porcentaje = $count / count($control); ?>
                <th class="text-center {{-- $this->toa_model->clase_cumplimiento_consumos($porcentaje) --}}">{{ fmtCantidad(100*$porcentaje, 0, TRUE) }}%</th>
            @endforeach
        </tr>
    </tfoot>
</table>

@endif
</div> <!-- fin content-module-main -->
