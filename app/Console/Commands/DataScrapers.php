<?php

namespace App\Console\Commands;

use App\Jobs\RunScraper;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;

class DataScrapers extends Command
{
    use DispatchesJobs;

    const JOBS_PER_CALL = 10;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        data:scrapers
        {--all : Run all available scrapers instead of processing them in chunks}
        {name? : Run only the scraper with this directory name}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run registered scrapers in a chunk-processing mode.';

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
     * @return int
     */
    public function handle(Filesystem $fs)
    {
        if ($this->option('all') && strlen($this->argument('name')) > 0) {
            $this->error('--all and <name> are mutually exclusive command options. Cannot run.');
            return 1;
        }

        if ($this->argument('name') && $fs->exists("providers/scrapers/{$this->argument('name')}"))
        {
            $this->scheduleScraperRun($this->argument('name'));
        } else {
            $scrapers = collect($fs->directories('providers/scrapers'))->map(function($dirName) {
                return str_replace('providers/scrapers/', '', $dirName);
            });

            if ($this->option('all') || $scrapers->count() < self::JOBS_PER_CALL) {
                $this->scheduleScrapers($scrapers);
            } else {
                $lastJob = 0;
                if ($fs->exists('lastScraperRun'))
                    $lastJob = $fs->get('lastScraperRun');

                $scrapers->slice($lastJob, self::JOBS_PER_CALL);
                $this->scheduleScrapers($scrapers);

                $nextJob = 0;
                if ($lastJob + self::JOBS_PER_CALL > $scrapers->count())
                    $nextJob = $lastJob + self::JOBS_PER_CALL;

                $fs->put('lastScraperRun', $nextJob);
            }
        }

        return 0;
    }

    /**
     * @param $scraperName string
     **/
    protected function scheduleScraperRun($scraperName)
    {
        $this->info("Scheduling run for {$scraperName}");
        $this->dispatch(new RunScraper($scraperName));
    }

    /**
     * @param $scrapers Collection
     **/
    protected function scheduleScrapers(Collection $scrapers)
    {
        $scrapers->each(function ($scraperName) {
            $this->scheduleScraperRun($scraperName);
        });
    }
}
