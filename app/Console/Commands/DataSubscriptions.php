<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DataSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run registered ical subscription updates.';

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
        //
    }
}
