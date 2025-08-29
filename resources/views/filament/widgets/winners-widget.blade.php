<x-filament::widget class="w-full">
	<x-filament::card class="w-full">
		<div class="space-y-4">
			<div class="flex items-center justify-between">
				<h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
					Ganadores de la sección anterior
				</h2>
			</div>

			@if (!$game)
				<div class="text-sm text-gray-500 dark:text-gray-400">No hay resultados previos disponibles.</div>
			@else
				<div class="text-sm text-gray-600 dark:text-gray-300">
					Lotería:
					<span class="font-medium text-gray-900 dark:text-gray-100">{{ $game->name }}</span>
					| Sesión:
					<span class="font-medium text-gray-900 dark:text-gray-100">{{ $game->date }}</span>
					| Pick3:
					<span class="font-mono">{{ $game->pick3_winning_number ?? '—' }}</span>
					| Pick4:
					<span class="font-mono">{{ $game->pick4_winning_number ?? '—' }}</span>
				</div>

				@if ($bets->isEmpty())
					<div class="text-sm text-gray-500 dark:text-gray-400">No hubo ganadores en la última sección.</div>
				@else
					<div class="overflow-x-auto">
						<table class="min-w-full fi-ta table">
							<thead>
								<tr class="border-b border-gray-200 dark:border-gray-700">
									<th class="py-2 px-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Jugador</th>
									<th class="py-2 px-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Lotería</th>
									<th class="py-2 px-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Modalidad</th>
									<th class="py-2 px-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Números jugados</th>
									<th class="py-2 px-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Número ganador</th>
									<th class="py-2 px-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Pago</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($bets as $row)
									<tr class="border-b border-gray-100 dark:border-gray-800 last:border-0 odd:bg-gray-50/40 dark:odd:bg-white/5">
										<td class="py-2 px-3 text-gray-900 dark:text-gray-100 whitespace-nowrap">
											{{ $row['user'] }}
										</td>
										<td class="py-2 px-3 text-gray-900 dark:text-gray-100 whitespace-nowrap">
											{{ $row['lotto'] }}
										</td>
										<td class="py-2 px-3">
											<span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
												{{ strtoupper($row['type']) }}
											</span>
										</td>
										<td class="py-2 px-3 text-gray-900 dark:text-gray-100">
											{{ $row['numbers'] }}
										</td>
										<td class="py-2 px-3">
											@if ($row['winning_number'])
												<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-mono font-semibold bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400">
													{{ $row['winning_number'] }}
												</span>
											@else
												<span class="text-gray-500 dark:text-gray-400">—</span>
											@endif
										</td>
										<td class="py-2 px-3 text-right font-semibold text-emerald-700 dark:text-emerald-400 whitespace-nowrap">
											$ {{ $row['payout'] }}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			@endif
		</div>
	</x-filament::card>
</x-filament::widget>
