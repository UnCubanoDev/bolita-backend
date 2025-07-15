<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;

class CreateDailyGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:create-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea los juegos para cada sesión y variante del día';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = now()->toDateString();
        $sessions = ['mañana', 'tarde', 'noche']; // Ajusta según tus sesiones
        $games = [
            ['name' => 'Georgia Lottery', 'variants' => ['pick3', 'pick4']],
            ['name' => 'Florida Lottery', 'variants' => ['pick3', 'pick4']],
            ['name' => 'New York Lottery', 'variants' => ['pick3', 'pick4']],
        ];

        foreach ($games as $game) {
            foreach ($game['variants'] as $variant) {
                foreach ($sessions as $session) {
                    Game::firstOrCreate([
                        'name' => $game['name'],
                        'date' => $date,
                        'type' => $variant,
                        'session_time' => $session,
                    ], [
                        'is_active' => true,
                    ]);
                }
            }
        }

        $this->info('Juegos creados para el día ' . $date);
    }
}
