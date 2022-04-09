<?php

namespace App\Console\Commands;

use App\Models\Bank;
use Illuminate\Console\Command;

class ImportBanksTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-banks {--silent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import banks table data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (Bank::count() > 0) {
            $this->line('Bank table data has already imported!');
            return;
        }

        if (!$this->confirm('Do you wish to continue importing data?') && !$this->option('silent')) {
            $this->line('Bank table data importing canceled!');
            return;
        }

        $scriptPath = database_path('sqlfiles/banks.sql');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');

        $this->line('Importing bank table data...');

        exec("mysql --user='{$username}' --password='{$password}' {$database} < '{$scriptPath}'");

        $this->info('Bank table data imported successfully!');
    }
}
