<!doctype html>
<html>
<head>
    <title>inventario fija</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="{{ asset('css/estilo.css') }}" />
    <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/view_inventario.js') }}"></script>
    <script type="text/javascript">
        js_base_url = '{{ asset('') }}';
    </script>
</head>
<body>

@foreach($hojasInventario as $hoja => $lineas)
<div class="print-heading">
	<table>
		<tr>
			<td>Fecha: ___________________________</td>
			<td><h2>Inventario: {{ $inventario }}</h2></td>
			<td class="ar"><h2>Hoja: {{ $hoja }}</h2></td>
		</tr>
		<tr>
			<td colspan="2">Auditor: _______________________________</td>
			<td class="ar">Digitador: _______________________________</td>
		</tr>
		<tr>
			<td colspan="2">Operario: ____________________________</td>
			<td><strong>F</strong>: FRENTE / <strong>A</strong>: ATRAS</td>
		</tr>
	</table>
</div> <!-- fin print-heading -->

<br>

<div class="print-main">
	<table>
		<thead>
			<tr>
				<th class="ac">ubic</th>
				<!-- <th class="ac">hu</th> -->
				<th class="ac">material</th>
				<th class="ac" style="width: 30%">descripcion</th>
				<th class="ac">lote</th>
				<th class="ac">cen</th>
				<th class="ac">alm</th>
				<th class="ac">UM</th>

				@if (!$ocultaStockSAP)
					<th class="ac">cant <br> sap</th>
				@endif

				<th class="ac">cant <br> fisico</th>

				@if (!$ocultaStockSAP)
					<th class="ac">F</th>
					<th class="ac">A</th>
				@endif

				<th class="ac" style="width: 8%">HU</th>
				<th class="ac" style="width: 18%">observacion</th>
			</tr>
		</thead>
		<tbody>
			<?php $sum_sap = 0; $lin = 0; ?>
			@foreach ($lineas as $detalle)
				<?php $lin += 1; ?>
				<tr>
					<td class="ac">{{ $detalle->ubicacion }}</td>
					{{-- <td class="ac">{{ $detalle->hu }}</td> --}}
					<td class="ac">{{ $detalle->catalogo }}</td>
					<td>{{ $detalle->descripcion }}</td>
					<td class="ac">{{ $detalle->lote }}</td>
					<td class="ac">{{ $detalle->centro }}</td>
					<td class="ac">{{ $detalle->almacen }}</td>
					<td class="ac">{{ $detalle->um }}</td>

					@if (!$ocultaStockSAP)
						<td class="ac">{{ fmtCantidad($detalle->stock_sap) }}</td>
					@endif

					<!-- cantidad física -->
					<td></td>

					@if (!$ocultaStockSAP)
						<!-- FRENTE -->
						<td></td>
						<!-- ATRAS -->
						<td></td>
					@endif

					<!-- HU -->
					<td></td>

					<!-- OBSERVACION -->
					<td>
						@if ($catalogo::find($detalle->catalogo)->es_seriado)
							<strong>[HU]</strong>
						@endif
					</td>
				</tr>
				<?php $sum_sap += (int) $detalle->stock_sap; ?>
			@endforeach

			@for($i=$lin; $i<20; $i++)
				<tr>
					<td>&nbsp;</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					@if (!$ocultaStockSAP)
						<td></td>
						<td></td>
						<td></td>
					@endif
				</tr>
			@endfor
			<!-- totales -->
			@if (!$ocultaStockSAP)
				<tr>
					<td colspan="7" class="no-border"></td>
					<td class="ac"><strong>{{ fmtCantidad($sum_sap) }}</strong></td>
					<td colspan="4" class="no-border"></td>
				</tr>
			@endif

			<tr>
				@if (!$ocultaStockSAP)
					<td class="no-border"></td>
				@endif
				<td colspan="8" class="ar no-border">TOTAL HOJA: _____________</td>
				<td colspan="3" class="no-border"></td>
			</tr>


		</tbody>
	</table>
</div> <!-- fin print-main -->
@endforeach

</body>
</html>
