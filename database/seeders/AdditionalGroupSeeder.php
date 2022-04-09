<?php

namespace Database\Seeders;

use App\Models\AdditionalGroup;
use Illuminate\Database\Seeder;

class AdditionalGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ingresso
        AdditionalGroup::create([
            'name' => [
                'pt-br' => 'Ingresso',
                'en' => 'Ticket',
                'es' => 'Billete',
            ],
            'selection_type' => 'single',
        ]);

        // Passagem Aérea
        AdditionalGroup::create([
            'name' => [
                'pt-br' => 'Passagem Aérea',
                'en' => 'Airfare',
                'es' => 'Boleto de Avión',
            ],
            'selection_type' => 'single',
        ]);

        // Alimentação
        AdditionalGroup::create([
            'name' => [
                'pt-br' => 'Alimentação',
                'en' => 'Food',
                'es' => 'Alimentación',
            ],
            'selection_type' => 'single',
        ]);

        // Seguro Viagem
        AdditionalGroup::create([
            'name' => [
                'pt-br' => 'Seguro Viagem',
                'en' => 'Travel Insurance',
                'es' => 'Viaje seguro',
            ],
            'selection_type' => 'single',
        ]);

        // Traslado
        AdditionalGroup::create([
            'name' => [
                'pt-br' => 'Traslado',
                'en' => 'Transfer',
                'es' => 'Traslado',
            ],
            'selection_type' => 'single',
        ]);
    }
}
