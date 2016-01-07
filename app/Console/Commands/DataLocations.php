<?php namespace App\Console\Commands;

use App\Jobs\AnnotateLocation;
use App\Model\Location;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class DataLocations extends Command
{
    use DispatchesJobs;

    protected $signature = 'data:locations';
    protected $description = 'Try annotating the known locations to extend available data';

    public function handle()
    {
        $this->info('Scheduling annotation for existing locations.');

        Location::all()->each(function (Location $l) {
            $this->line("{$l->human_name}");
            $this->dispatch(new AnnotateLocation($l));
        });
    }
}