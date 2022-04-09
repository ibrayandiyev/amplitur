<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;

class ImportWorldTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-worlds {--silent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import wolrd tables data like countries, states and cities';

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
        if (Country::count() > 0) {
            $this->line('World tables data has already imported!');
            return;
        }

        if (!$this->confirm('Do you wish to continue importing data?') && !$this->option('silent')) {
            $this->line('World tables data importing canceled!');
            return;
        }

        $scriptPath = database_path('sqlfiles/world.sql');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');

        $this->line('Importing world tables data...');

        exec("mysql --user='{$username}' --password='{$password}' {$database} < '{$scriptPath}'");

        $this->info('World tables data imported successfully!');
    }
}
