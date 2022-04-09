<?php

namespace App\Console\Commands;

use App\Services\QuotationService;
use Illuminate\Console\Command;

class ImportCurrenciesQuotations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-currencies-quotations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import currencies quotations from external API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(QuotationService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->service->run();
    }
}
