<?php

namespace App\Console\Commands;

use App\Jobs\RunScraper;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class DataScrapers extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:scrapers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run registered scrapers.';

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
        $this->dispatch(new RunScraper('karreraklub'));
    }
}
