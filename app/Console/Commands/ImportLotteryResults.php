<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportLotteryResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-lottery-results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $games = \App\Models\Game::whereNull('winning_number')->get();

        foreach ($games as $game) {
            // Aquí deberías reemplazar con tu API real
            $response = Http::get('https://api.ejemplo.com/results', [
                'state' => strtolower($game->name),
                'date' => $game->date->format('Y-m-d'),
            ]);

            if ($response->successful() && $result = $response->json('winning_number')) {
                $game->update([
                    'winning_number' => $result,
                    'result_imported_at' => now(),
                ]);

                $this->info("Resultado actualizado: {$game->name} {$game->date} → {$result}");
            }
        }
    }
}
