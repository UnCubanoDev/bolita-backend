<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Setting::truncate(); // Elimina todos los registros

        $settings = [
            // Horarios generales para Georgia
            [
                'key' => 'app_version',
                'value' => '1',
                'type' => 'number',
                'label' => 'Version de la aplicación',
                'description' => '',
                'group' => 'system'
            ],
            [
                'key' => 'georgia_morning_start',
                'value' => '09:00',
                'type' => 'time',
                'label' => 'Inicio Georgia Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'georgia_morning_end',
                'value' => '12:20',
                'type' => 'time',
                'label' => 'Fin Georgia Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'georgia_noon_start',
                'value' => '17:00',
                'type' => 'time',
                'label' => 'Inicio Georgia Mediodía',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'georgia_noon_end',
                'value' => '18:45',
                'type' => 'time',
                'label' => 'Fin Georgia Mediodía',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'georgia_evening_start',
                'value' => '17:00',
                'type' => 'time',
                'label' => 'Inicio Georgia Tarde',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'georgia_evening_end',
                'value' => '18:50',
                'type' => 'time',
                'label' => 'Fin Georgia Tarde',
                'description' => '',
                'group' => 'betting'
            ],
            // Horarios generales para Florida
            [
                'key' => 'florida_morning_start',
                'value' => '09:00',
                'type' => 'time',
                'label' => 'Inicio Florida Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'florida_morning_end',
                'value' => '13:20',
                'type' => 'time',
                'label' => 'Fin Florida Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'florida_evening_start',
                'value' => '17:00',
                'type' => 'time',
                'label' => 'Inicio Florida Tarde',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'florida_evening_end',
                'value' => '21:30',
                'type' => 'time',
                'label' => 'Fin Florida Tarde',
                'description' => '',
                'group' => 'betting'
            ],
            // Horarios generales para New York
            [
                'key' => 'newyork_morning_start',
                'value' => '10:00',
                'type' => 'time',
                'label' => 'Inicio New York Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'newyork_morning_end',
                'value' => '14:20',
                'type' => 'time',
                'label' => 'Fin New York Mañana',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'newyork_evening_start',
                'value' => '17:00',
                'type' => 'time',
                'label' => 'Inicio New York Tarde',
                'description' => '',
                'group' => 'betting'
            ],
            [
                'key' => 'newyork_evening_end',
                'value' => '22:20',
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
                'value' => '80',
                'type' => 'number',
                'label' => 'Multiplicador pago Fijo',
                'description' => 'Pago por cada peso apostado en Fijo',
                'group' => 'betting'
            ],
            [
                'key' => 'payout_corrido',
                'value' => '25',
                'type' => 'number',
                'label' => 'Multiplicador pago Corrido',
                'description' => 'Pago por cada peso apostado en Corrido',
                'group' => 'betting'
            ],
            [
                'key' => 'payout_parle',
                'value' => '1000',
                'type' => 'number',
                'label' => 'Multiplicador pago Parle',
                'description' => 'Pago por cada peso apostado en Parle',
                'group' => 'betting'
            ],
            [
                'key' => 'recharge_card_number',
                'value' => '9205-9598-7864-5481', // Cambia por el número real o déjalo vacío
                'type' => 'string',
                'label' => 'Número de Tarjeta para Recargas',
                'description' => 'Número de tarjeta o cuenta a la que se deben hacer las recargas',
                'group' => 'recharge'
            ],
            [
                'key' => 'phone_number',
                'value' => '58979145', // Cambia por el número real o déjalo vacío
                'type' => 'string',
                'label' => 'Número de Teléfono para Recargas',
                'description' => 'Número de teléfono al cual se debe confirmar las recargas',
                'group' => 'recharge'
            ],
            [
                'key' => 'limit_fijo',
                'value' => '5000', // Cambia por el número real o déjalo vacío
                'type' => 'number',
                'label' => 'Limite Fijo',
                'description' => 'Número máximo de dinero en apuestas totales de tipo Fijo',
                'group' => 'betting'
            ],
            [
                'key' => 'limit_pick3',
                'value' => '5000', // Cambia por el número real o déjalo vacío
                'type' => 'number',
                'label' => 'Limite Pick 3',
                'description' => 'Número máximo de dinero en apuestas totales de tipo Pick 3',
                'group' => 'betting'
            ],
            [
                'key' => 'limit_pick4',
                'value' => '5000', // Cambia por el número real o déjalo vacío
                'type' => 'number',
                'label' => 'Limite Pick 4',
                'description' => 'Número máximo de dinero en apuestas totales de tipo Pick 4',
                'group' => 'betting'
            ],
            [
                'key' => 'limit_corrido',
                'value' => '5000', // Cambia por el número real o déjalo vacío
                'type' => 'number',
                'label' => 'Limite Corrido',
                'description' => 'Número máximo de dinero en apuestas totales de tipo Corrido',
                'group' => 'betting'
            ],
            [
                'key' => 'limit_parle',
                'value' => '5000', // Cambia por el número real o déjalo vacío
                'type' => 'number',
                'label' => 'Limite Parle',
                'description' => 'Número máximo de dinero en apuestas totales de tipo Parle',
                'group' => 'betting'
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
