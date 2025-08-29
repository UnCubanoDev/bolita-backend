<x-filament::widget class="w-full">
    <x-filament::card class="w-full">
<div class="p-4">
    <div class="text-lg font-semibold mb-2">Ganadores de la sección anterior</div>

    @if (!$game)
        <div class="text-sm text-gray-500">No hay resultados previos disponibles.</div>
    @else
        <div class="text-sm text-gray-600 mb-3">
            Lotería: <span class="font-medium">{{ $game->name }}</span> |
            Sesión: <span class="font-medium">{{ $game->date }}</span> |
            Pick3: <span class="font-medium">{{ $game->pick3_winning_number ?? '—' }}</span> |
            Pick4: <span class="font-medium">{{ $game->pick4_winning_number ?? '—' }}</span>
        </div>

        @if ($bets->isEmpty())
            <div class="text-sm text-gray-500">No hubo ganadores en la última sección.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left border-b">
                        <tr>
                            <th class="py-2 pr-4">Jugador</th>
                            <th class="py-2 pr-4">Lotería</th>
                            <th class="py-2 pr-4">Modalidad</th>
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
    @endif
</div>

    </x-filament::card>
</x-filament::widget>
