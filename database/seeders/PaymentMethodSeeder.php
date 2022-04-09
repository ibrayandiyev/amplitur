<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createNationals();
        $this->createInternationals();
    }

    /**
     * [createNationals description]
     *
     * @return  [type]  [return description]
     */
    public function createNationals()
    {
        PaymentMethod::factory()->create([
            'name' => 'Cartão de Crédito',
            'code' => 'credit-card',
            'category' => 'national',
        ]);

        PaymentMethod::factory()->create([
            'name' => 'Boleto Bancário Bradesco',
            'code' => 'boleto-bancario-bradesco',
            'category' => 'national',
        ]);

        PaymentMethod::factory()->create([
            'name' => 'Boleto Bancário Itaú',
            'code' => 'boleto-bancario-itau',
            'category' => 'national',
        ]);

        PaymentMethod::factory()->create([
            'name' => 'Cheque',
            'code' => 'cheque',
            'category' => 'national',
        ]);

        PaymentMethod::factory()->create([
            'name' => 'Dinheiro',
            'code' => 'dinheiro',
            'category' => 'national',
        ]);

        PaymentMethod::factory()->create([
            'name' => 'Transferência Bancária Bradesco',
            'code' => 'transferencia-bancaria-bradesco',
            'category' => 'national',
        ]);

        PaymentMethod::factory()->create([
            'name' => 'Transferência Bancária Itaú',
            'code' => 'transferencia-bancaria-itau',
            'category' => 'national',
        ]);
    }

    /**
     * [createInternationals description]
     *
     * @return  [type]  [return description]
     */
    public function createInternationals()
    {
        PaymentMethod::factory()->create([
            'name' => 'Cartão de Crédito',
            'code' => 'credit-card',
            'category' => 'international',
        ]);

        PaymentMethod::factory()->create([
            'name' => 'Paypal',
            'code' => 'paypal',
            'category' => 'international',
        ]);
    }
}
