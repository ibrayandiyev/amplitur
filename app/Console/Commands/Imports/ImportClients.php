<?php

namespace App\Console\Commands\Imports;

use App\Services\Imports\ImportClientsService;
use App\Services\QuotationService;
use Illuminate\Console\Command;

class ImportClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amp:import_clients {skip=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customers from older system.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ImportClientsService $service)
    {
        parent::__construct();

        $this->service = $service;
		$this->service->setCommand($this);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$skip = $this->argument('skip');
        $this->service->setSkip($skip);
        $this->service->run();
    }
}
