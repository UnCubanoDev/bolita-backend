<?php

namespace App\Services;

use App\Models\Setting;
use Carbon\Carbon;

class BetValidationService
{
    public function canPlaceBet(string $type, string $sessionTime): bool
    {
        $now = Carbon::now('America/Havana');
        $currentTime = $now->format('H:i');

        return $this->isWithinValidTimeRange($currentTime, $sessionTime);
    }

    private function isWithinValidTimeRange(string $currentTime, string $sessionTime): bool
    {
        // $morningStart = Setting::get('morning_session_start', '06:00');
        $morningEnd = Setting::get('morning_session_end', '11:45');
        // $eveningStart = Setting::get('evening_session_start', '14:00');
        $eveningEnd = Setting::get('evening_session_end', '20:45');

        if ($sessionTime === 'morning') {
            return $currentTime <= $morningEnd;
        }

        if ($sessionTime === 'evening') {
            return $currentTime <= $eveningEnd;
        }

        return false;
    }

    public function canPlaceBetDynamic(string $game, string $sessionTime): bool
    {
        $now = now('America/Havana');
        $currentTime = $now->format('H:i');

        // Normaliza el nombre del juego (sin espacios y en minúsculas)
        $prefix = strtolower(str_replace(' ', '', $game)) . '_' . strtolower($sessionTime);

        $start = \App\Models\Setting::get("{$prefix}_start", '00:00');
        $end   = \App\Models\Setting::get("{$prefix}_end", '23:59');

        return $currentTime >= $start && $currentTime <= $end;
    }

    public function getNextValidTime(): string
    {
        $now = Carbon::now('America/Havana');
        $currentTime = $now->format('H:i');

        $morningStart = Setting::get('morning_session_start', '06:00');
        $eveningStart = Setting::get('evening_session_start', '14:00');

        if ($currentTime < $morningStart) {
            return $morningStart;
        }

        if ($currentTime > Setting::get('morning_session_end', '11:45') &&
            $currentTime < $eveningStart) {
            return $eveningStart;
        }

        if ($currentTime > Setting::get('evening_session_end', '20:45')) {
            return $morningStart;
        }

        return null;
    }

    public function validateWinningNumber(string $winningNumber, string $sessionTime): bool
    {
        // Aquí puedes implementar la lógica específica para validar el número ganador
        // según la sesión (mañana/tarde)
        // Por ejemplo, podrías tener diferentes rangos o reglas para cada sesión

        if ($sessionTime === 'morning') {
            // Lógica de validación para números de la mañana
            return true; // Implementa tu lógica específica
        }

        if ($sessionTime === 'evening') {
            // Lógica de validación para números de la tarde
            return true; // Implementa tu lógica específica
        }

        return false;
    }

    public function getNextAvailableSession(): ?string
    {
        $now = Carbon::now('America/Havana');
        $cutoffTime = Carbon::createFromTime(21, 0, 0, 'America/Havana'); // 9:00 PM
        $startTime = Carbon::createFromTime(6, 0, 0, 'America/Havana');   // 6:00 AM

        if ($now->lessThan($startTime)) {
            return $now->copy()->setTime(6, 0)->format('H:i');
        }

        if ($now->greaterThanOrEqualTo($cutoffTime)) {
            return $now->copy()->addDay()->setTime(6, 0)->format('H:i');
        }

        return null; // apuestas permitidas
    }

}
