<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\DefibrillatorController;
use Illuminate\Console\Command;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'defibrillators:import {--full : Perform a full import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the import process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $full = $this->option('full');

        $this->info('Starting import process...');

        if ($full) {
            $this->info('Full import requested');
            DefibrillatorController::import(true, function ($processed) {
                $this->info('Processed ' . $processed . ' entries');
            });
        } else {
            $this->info('Basic import requested');
            DefibrillatorController::import(false, function ($processed) {
                $this->info('Processed ' . $processed . ' entries');
            });
        }

        $this->info('Import process completed');
    }
}