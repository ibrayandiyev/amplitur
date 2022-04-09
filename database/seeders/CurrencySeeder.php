<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyQuotation;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $real = Currency::factory()->create([
            'name' => 'Real',
            'code' => 'BRL',
            'symbol' => 'R$',
        ]);

        $euro = Currency::factory()->create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
        ]);

        $libra = Currency::factory()->create([
            'name' => 'Libra',
            'code' => 'GBP',
            'symbol' => '£',
        ]);

        $dolar = Currency::factory()->create([
            'name' => 'Dólar',
            'code' => 'USD',
            'symbol' => '$',
        ]);

        // BRL - BRL
        CurrencyQuotation::create([
            'name' => 'BRL-BRL (Real)',
            'origin_currency_id' => $real->id,
            'target_currency_id' => $real->id,
            'quotation' => 1.0,
            'spread' => 1.0,
        ]);

        // BRL - EUR
        CurrencyQuotation::create([
            'name' => 'BRL-EUR (Euro Turismo)',
            'origin_currency_id' => $real->id,
            'target_currency_id' => $euro->id,
            'quotation' => 6.64,
            'spread' => 1.10,
        ]);

        // BRL - GBP
        CurrencyQuotation::create([
            'name' => 'BRL-GBP (Libra Turismo)',
            'origin_currency_id' => $real->id,
            'target_currency_id' => $euro->id,
            'quotation' => 7.48,
            'spread' => 1.10,
        ]);

        // BRL - USD
        CurrencyQuotation::create([
            'name' => 'BRL-USD (Dólar IATA)',
            'origin_currency_id' => $real->id,
            'target_currency_id' => $dolar->id,
            'quotation' => 7.48,
            'spread' => 1.0,
        ]);

        // BRL - USD
        CurrencyQuotation::create([
            'name' => 'BRL-USD (Dólar Turismo)',
            'origin_currency_id' => $real->id,
            'target_currency_id' => $dolar->id,
            'quotation' => 7.48,
            'spread' => 1.10,
        ]);

        // EUR - EUR
        CurrencyQuotation::create([
            'name' => 'EUR-EUR (Euro)',
            'origin_currency_id' => $euro->id,
            'target_currency_id' => $real->id,
            'quotation' => 1.0,
            'spread' => 1.0,
        ]);
        
        // EUR - BRL
        CurrencyQuotation::create([
            'name' => 'EUR-BRL (Euro - BRL)',
            'origin_currency_id' => $euro->id,
            'target_currency_id' => $real->id,
            'quotation' => 0.15,
            'spread' => 1.10,
        ]);
            
        // EUR - GBP
        CurrencyQuotation::create([
            'name' => 'EUR-GBP (Euro - GBP)',
            'origin_currency_id' => $euro->id,
            'target_currency_id' => $libra->id,
            'quotation' => 1.13,
            'spread' => 1.05,
        ]);
            
        // EUR - USD
        CurrencyQuotation::create([
            'name' => 'EUR-USD (Euro - USD)',
            'origin_currency_id' => $euro->id,
            'target_currency_id' => $dolar->id,
            'quotation' => 0.82,
            'spread' => 1.05,
        ]);

        // GBP - GBP
        CurrencyQuotation::create([
            'name' => 'GBP-GBP (Libra)',
            'origin_currency_id' => $libra->id,
            'target_currency_id' => $libra->id,
            'quotation' => 1.0,
            'spread' => 1.0,
        ]);

        // GBP - BRL
        CurrencyQuotation::create([
            'name' => 'GBP-BRL (Libra - BRL)',
            'origin_currency_id' => $libra->id,
            'target_currency_id' => $real->id,
            'quotation' => 0.13,
            'spread' => 1.05,
        ]);

        // GBP - EUR
        CurrencyQuotation::create([
            'name' => 'GBP-EUR (Libra - EUR)',
            'origin_currency_id' => $libra->id,
            'target_currency_id' => $euro->id,
            'quotation' => 0.89,
            'spread' => 1.05,
        ]);

        // GBP - USD
        CurrencyQuotation::create([
            'name' => 'GBP-EUR (Libra - EUR)',
            'origin_currency_id' => $libra->id,
            'target_currency_id' => $dolar->id,
            'quotation' => 0.82,
            'spread' => 1.05,
        ]);

        // USD - USD
        CurrencyQuotation::create([
            'name' => 'USD-USD (Dólar)',
            'origin_currency_id' => $dolar->id,
            'target_currency_id' => $dolar->id,
            'quotation' => 1.0,
            'spread' => 1.0,
        ]);

        // USD - BRL
        CurrencyQuotation::create([
            'name' => 'USD-BRL (Dólar - BRL)',
            'origin_currency_id' => $dolar->id,
            'target_currency_id' => $real->id,
            'quotation' => 0.18,
            'spread' => 1.10,
        ]);

        // USD - EUR
        CurrencyQuotation::create([
            'name' => 'USD-EUR (Dólar - EUR)',
            'origin_currency_id' => $dolar->id,
            'target_currency_id' => $euro->id,
            'quotation' => 1.22,
            'spread' => 1.05,
        ]);

        // USD - GBP
        CurrencyQuotation::create([
            'name' => 'USD-GBP (Dólar - GBP)',
            'origin_currency_id' => $dolar->id,
            'target_currency_id' => $libra->id,
            'quotation' => 1.37,
            'spread' => 1.05,
        ]);
    }
}
