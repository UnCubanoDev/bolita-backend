<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Horarios generales para Georgia
            [
                'key' => 'georgia_morning_start',
                'value' => '06:00',
                'type' => 'time',
                'label' => 'Inicio Georgia Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'georgia_morning_end',
                'value' => '11:45',
                'type' => 'time',
                'label' => 'Fin Georgia Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'georgia_evening_start',
                'value' => '14:00',
                'type' => 'time',
                'label' => 'Inicio Georgia Tarde',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'georgia_evening_end',
                'value' => '20:45',
                'type' => 'time',
                'label' => 'Fin Georgia Tarde',
                'description' => '',
                'group' => 'betting'
            ],
            // Horarios generales para Florida
            [
                'key' => 'florida_morning_start',
                'value' => '06:00',
                'type' => 'time',
                'label' => 'Inicio Florida Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'florida_morning_end',
                'value' => '11:45',
                'type' => 'time',
                'label' => 'Fin Florida Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'florida_evening_start',
                'value' => '14:00',
                'type' => 'time',
                'label' => 'Inicio Florida Tarde',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'florida_evening_end',
                'value' => '20:45',
                'type' => 'time',
                'label' => 'Fin Florida Tarde',
                'description' => '',
                'group' => 'betting'
            ],
            // Horarios generales para New York
            [
                'key' => 'newyork_morning_start',
                'value' => '06:00',
                'type' => 'time',
                'label' => 'Inicio New York Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'newyork_morning_end',
                'value' => '11:45',
                'type' => 'time',
                'label' => 'Fin New York Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'newyork_evening_start',
                'value' => '14:00',
                'type' => 'time',
                'label' => 'Inicio New York Tarde',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'newyork_evening_end',
                'value' => '20:45',
                'type' => 'time',
                'label' => 'Fin New York Tarde',
                'description' => '',
                'group' => 'betting'
            ],
            // Multiplicadores de pago para tipos de apuesta
            [
                'key' => 'payout_pick3',
                'value' => '500',
                'type' => 'number',
                'label' => 'Multiplicador pago Pick 3',
                'description' => 'Pago por cada peso apostado en Pick 3',
                'group' => 'betting'
            ],
            [
                'key' => 'payout_pick4',
                'value' => '5000',
                'type' => 'number',
                'label' => 'Multiplicador pago Pick 4',
                'description' => 'Pago por cada peso apostado en Pick 4',
                'group' => 'betting'
            ],
            [
                'key' => 'payout_fijo',
                'value' => '50',
                'type' => 'number',
                'label' => 'Multiplicador pago Fijo',
                'description' => 'Pago por cada peso apostado en Fijo',
                'group' => 'betting'
            ],
            [
                'key' => 'payout_corrido',
                'value' => '20',
                'type' => 'number',
                'label' => 'Multiplicador pago Corrido',
                'description' => 'Pago por cada peso apostado en Corrido',
                'group' => 'betting'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}