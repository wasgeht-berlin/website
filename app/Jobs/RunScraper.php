<?php

namespace App\Jobs;

use App\Model\Event;
use App\Model\Location;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Parser;

class RunScraper extends Job implements SelfHandling
{
    protected $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Filesystem $fs)
    {
        $scraperDir = 'providers/scrapers/' . $this->name;

        $config = $this->loadConfig($fs, $scraperDir);
        if (is_null($config)) {
            // TODO: automatically send an issue to github notifying about the missing configuration file?
            return;
        }

        $this->runScraper($config, $scraperDir);
    }

    protected function loadConfig(Filesystem $fs, $scraperDir)
    {
        $scraperConfigFile = $scraperDir . '/scraper.yml';

        if (!$fs->exists($scraperConfigFile))
            return null;

        $configString = $fs->get($scraperConfigFile);

        $parser = new Parser();
        $config = $parser->parse($configString);

        if (!is_array($config) || !count($config) >= 2)
            return null;

        return $config;
    }

    /**
     * @param $config
     * @param $scraperDir
     **/
    protected function runScraper($config, $scraperDir)
    {
        //$cmd = sprintf('docker --rm ubuntu:14.04 -e LANG=C.UTF-8 %s %s', $interpreter, $mainFile);
        $cmd = sprintf('%s %s', $config['interpreter'], $config['main']);

        $process = new Process($cmd, storage_path('app/' . $scraperDir));
        $process->mustRun();

        $output = $process->getOutput();

        $events = $this->parseOutput($config, $output);

        foreach ($events as $info) {
            // TODO: fill missing optional keys with null on info arrays
            // TODO: fail early on missing required keys

            /*
             * TODO:
             *
             * - find or create location
             * - add tags
             */

            $loc = Location::whereHumanName($info['location']['human_name'])->first();

            if (!$loc) {
                $loc = Location::create([
                    'human_name' => $info['location']['human_name'],
                    // TODO: human_street_address, lat, lng, url
                ]);
            }

            $event = Event::whereHash($info['hash'])->first();

            if (!$event) {
                $event = Event::create([
                    'title'         => $info['title'],
                    'description'   => $info['description'],
                    'hash'          => $info['hash'],
                    'starting_time' => Carbon::parse($info['starting_time']),
                    'url'           => $info['url'],
                    // TODO: hash, notes
                ]);

                $event->location()->associate($loc);
                $event->save();
            }
        }
    }

    /**
     * Output can be delivered as either json or yaml,
     * parsing defaults to json. If the scraper returns it's result
     * as yaml array, the configuration option output needs to be set accordingly.
     *
     * @param $config
     * @param $output
     */
    protected function parseOutput($config, $output)
    {
        if (isset($config['output'])) {
            switch ($config['output']) {
                case 'yaml':
                    $parser = new Parser();
                    $result = $parser->parse($output);
                    break;

                case 'json':
                default:
                    $result = json_decode($output, true);
                    break;
            }
        } else {
            $result = json_decode($output, true);
        }

        return $result;
    }
}
