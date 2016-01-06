<?php

namespace App\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Filesystem\Filesystem;
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
    public function handle(Filesystem $fs)
    {
        $scraperDir = 'providers/scrapers/' . $this->dir;
        $scraperConfigFile = $scraperDir . '/scraper.yml';

        if (!$fs->exists($scraperConfigFile))
        {
            // TODO: throw error
            return;
        }

        $configString = $fs->get($scraperConfigFile);

        $parser = new Parser();
        $config = $parser->parse($configString);

        if (!is_array($config) || !count($config) == 1) {
            // TODO: throw exception for invalid format
            return;
        }

        $name = $config[0]['name'];
        $mainFile = $config[0]['main'];
        $interpreter = $config[0]['interpreter'];

        //$cmd = sprintf('docker --rm ubuntu:14.04 -e LANG=C.UTF-8 %s %s', $interpreter, $mainFile);
        $cmd = sprintf('%s %s', $interpreter, $mainFile);

        dd($cmd);
    }
}
