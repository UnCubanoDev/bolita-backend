<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Procesar resultados del mediodía (12:00 PM)
        $schedule->command('process:game-results')
            ->dailyAt('12:00')
            ->timezone('America/Havana');

        // Procesar resultados de la noche (9:00 PM)
        $schedule->command('process:game-results')
            ->dailyAt('21:00')
            ->timezone('America/Havana');

        // Importar resultados de la lotería
        $schedule->command('import:lottery-results')
            ->dailyAt('20:00')
            ->timezone('America/Havana');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
