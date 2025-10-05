<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestCMD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-c-m-d';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (config('app.env') == 'production') {
            $this->info('Production TestCMD command executed successfully.');
            Log::info('Production TestCMD command executed successfully.');
            return Command::SUCCESS;
        } else {
            $this->info('Locally TestCMD command executed successfully.');
            Log::info('Locally TestCMD command executed successfully.');
            return Command::SUCCESS;
        }
    }
}
