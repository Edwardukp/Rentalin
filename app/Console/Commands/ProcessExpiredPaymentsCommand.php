<?php

namespace App\Console\Commands;

use App\Jobs\ProcessExpiredPayments;
use Illuminate\Console\Command;

class ProcessExpiredPaymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:process-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process expired payments and update their status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing expired payments...');

        ProcessExpiredPayments::dispatch();

        $this->info('Expired payments processing job dispatched successfully.');

        return 0;
    }
}
