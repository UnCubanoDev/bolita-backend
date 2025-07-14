<x-filament::widget class="w-full">
    <x-filament::card class="w-full">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Últimos resultados de Loterías</h2>
            <x-filament::button wire:click="loadData">
                🔄 Actualizar
            </x-filament::button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
            @foreach ($this->loterias as $loteria)
                <div class="border rounded-lg p-4 shadow">
                    <div class="flex items-center space-x-3 mb-2">
                        <img src="https://tuboliteros.com{{ $loteria['logo'] }}" class="h-10" alt="{{ $loteria['nombre'] }}">
                        <h3 class="text-lg font-semibold">{{ $loteria['nombre'] }}</h3>
                    </div>
                    <p><strong>Último Pick 3:</strong> {{ $loteria['ultima']['pick3'] }}</p>
                    <p><strong>Último Pick 4:</strong> {{ $loteria['ultima']['pick4'] }}</p>
                    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($loteria['ultima']['fecha'])->format('d/m/Y H:i') }}</p>
                    <p><strong>Horario:</strong> {{ ucfirst($loteria['ultima']['horario']) }}</p>
                </div>
            @endforeach
        </div>
    </x-filament::card>
</x-filament::widget>
