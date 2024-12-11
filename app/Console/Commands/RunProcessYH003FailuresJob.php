<?php

namespace App\Console\Commands;

use App\Jobs\ProcessYH003FailuresJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunProcessYH003FailuresJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yh003:failures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta el job ProcessYH003FailuresJob';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ProcessYH003FailuresJob::dispatch();

        // $this->info('Job ProcessYH003FailuresJob ha sido ejecutado correctamente.');
        Log::info('Job ProcessYH003FailuresJob ha sido ejecutado correctamente.');
    }
}
