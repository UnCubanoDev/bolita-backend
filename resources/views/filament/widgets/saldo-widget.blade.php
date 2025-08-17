<x-filament::widget>
    <x-filament::card>
        <div class="px-4 py-3 space-y-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Resumen de Saldos
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Saldo Total -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Saldo Total
                    </div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        ${{ number_format($totalWallet, 2) }}
                    </div>
                </div>

                <!-- Saldo Congelado -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-orange-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Saldo Congelado
                    </div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        ${{ number_format($totalFrozen, 2) }}
                    </div>
                </div>

                <!-- Saldo Disponible -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Saldo Disponible
                    </div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        ${{ number_format($totalAvailable, 2) }}
                    </div>
                </div>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
