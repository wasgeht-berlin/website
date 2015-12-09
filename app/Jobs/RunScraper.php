<?php

namespace App\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Symfony\Component\Yaml\Parser;

class RunScraper extends Job implements SelfHandling
{
    protected $dir;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $config_string = file_get_contents(base_path() . '/../wasgeht-data-providers/scrapers/' . $this->dir . '/scraper.yml');

        $parser = new Parser();
        $config = $parser->parse($config_string);

        if (!is_array($config) || !count($config) == 1) {
            // TODO: throw exception for invalid format
            return;
        }

        $name = $config[0]['name'];
        $mainFile = $config[0]['main'];
        $interpreter = $config[0]['interpreter'];

        $cmd = sprintf('docker --rm ubuntu:14.04 -e LANG=C.UTF-8 %s %s', $interpreter, $mainFile);

        dd($cmd);
    }
}
