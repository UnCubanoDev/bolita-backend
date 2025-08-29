<x-filament::widget class="w-full">
    <x-filament::card class="w-full">
<div class="p-4 space-y-3">
	<div class="text-lg font-semibold">Ganadores</div>

	<div class="grid grid-cols-1 md:grid-cols-3 gap-3">
		<div>
			<label class="text-sm text-gray-600">Juego</label>
			<select wire:model="selectedGameName" class="w-full border rounded px-2 py-1">
				<option value="">Todos</option>
				@foreach ($gameNames as $name)
					<option value="{{ $name }}">{{ $name }}</option>
				@endforeach
			</select>
		</div>
		<div>
			<label class="text-sm text-gray-600">Día</label>
			<select wire:model="selectedDate" class="w-full border rounded px-2 py-1">
				<option value="">Todos</option>
				@foreach ($dates as $day)
					<option value="{{ $day }}">{{ $day }}</option>
				@endforeach
			</select>
		</div>
		<div>
			<label class="text-sm text-gray-600">Variante</label>
			<select wire:model="selectedVariant" class="w-full border rounded px-2 py-1">
				<option value="">Todas</option>
				@foreach ($variants as $v)
					<option value="{{ $v }}">{{ strtoupper($v) }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="text-sm text-gray-600">
		@if ($game)
			Lotería: <span class="font-medium">{{ $game->name }}</span>
			@if ($game->date)
				| Día: <span class="font-medium">{{ $game->date }}</span>
			@endif
			| Pick3: <span class="font-medium">{{ $game->pick3_winning_number ?? '—' }}</span>
			| Pick4: <span class="font-medium">{{ $game->pick4_winning_number ?? '—' }}</span>
		@else
			<span class="text-gray-500">Sin contexto de juego seleccionado.</span>
		@endif
	</div>

	@if ($bets->isEmpty())
		<div class="text-sm text-gray-500">No hay ganadores con los filtros seleccionados.</div>
	@else
		<div class="overflow-x-auto">
			<table class="min-w-full text-sm">
				<thead class="text-left border-b">
					<tr>
						<th class="py-2 pr-4">Jugador</th>
						<th class="py-2 pr-4">Lotería</th>
						<th class="py-2 pr-4">Día</th>
						<th class="py-2 pr-4">Variante</th>
						<th class="py-2 pr-4">Números jugados</th>
						<th class="py-2 pr-4">Número ganador</th>
						<th class="py-2 pr-4">Pago</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($bets as $row)
						<tr class="border-b last:border-0">
							<td class="py-2 pr-4">{{ $row['user'] }}</td>
							<td class="py-2 pr-4">{{ $row['lotto'] }}</td>
							<td class="py-2 pr-4">{{ $row['date'] }}</td>
							<td class="py-2 pr-4 uppercase">{{ $row['type'] }}</td>
							<td class="py-2 pr-4">{{ $row['numbers'] }}</td>
							<td class="py-2 pr-4 font-mono">{{ $row['winning_number'] ?? '—' }}</td>
							<td class="py-2 pr-4 font-semibold">$ {{ $row['payout'] }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	@endif
</div>

    </x-filament::card>
</x-filament::widget>
