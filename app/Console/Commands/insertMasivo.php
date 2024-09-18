<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\InsertMasivoJob;


class insertMasivo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:masivo';

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
        InsertMasivoJob::dispatch();
    }
}
