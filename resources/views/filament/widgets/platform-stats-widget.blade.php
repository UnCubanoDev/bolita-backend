<div class="p-4">
	<div class="text-lg font-semibold mb-3">Estadísticas de la plataforma</div>

	<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
		<div class="rounded border p-4">
			<div class="text-sm text-gray-600">Recaudado</div>
			<div class="text-2xl font-bold">$ {{ $collected }}</div>
		</div>
		<div class="rounded border p-4">
			<div class="text-sm text-gray-600">Pagado a ganadores</div>
			<div class="text-2xl font-bold">$ {{ $paid }}</div>
		</div>
		<div class="rounded border p-4">
			<div class="text-sm text-gray-600">Ganancia de la plataforma</div>
			<div class="text-2xl font-bold @if($profit_raw < 0) text-red-600 @else text-emerald-600 @endif">
				$ {{ $profit }}
			</div>
		</div>
	</div>
</div>

