<?php

namespace App\Jobs;

use App\Exceptions\InvalidEventException;
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
            $this->raiseIssue("{$this->name} has no valid scraper configuration", '', ['scraper', 'bug']);

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

    protected function raiseIssue($title, $body, array $labels = [])
    {
        if (strlen($body) == 0) $body = $title;
        dispatch(new CreateGitHubIssue('wasgeht-berlin', 'data-providers', $title, $body, $labels));
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
            if (!$this->validateEvent($info)) continue;

            $this->parseEvent($info);
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

    /**
     * @param $info
     * @return bool
     **/
    protected function validateEvent($info)
    {
        try {
            $infoKeys = array_keys($info);

            if (array_diff(['location', 'title', 'description', 'url', 'hash'], $infoKeys) !== []
                || !array_key_exists('human_name', $info['location'])
            ) {
                throw new InvalidEventException("Invalid format.");
            }

            return true;
        } catch (InvalidEventException $e) {
            $dump = json_encode($info, JSON_PRETTY_PRINT);

            $this->raiseIssue(
                "{$this->name} emits invalid events.",
                "{$this->name} emits an invalid event format. This is most likely due"
                . "to missing required keys. Below is a dump of the offending event info:\n\n"
                . "```json\n{$dump}\n```\n",
                ['scraper', 'bug']
            );

            return false;
        }
    }

    /**
     * @param $info
     **/
    protected function parseEvent($info)
    {
        $loc = Location::whereHumanName($info['location']['human_name'])->first();

        if (!$loc) $loc = $this->createLocation($info);

        $event = Event::whereHash($info['hash'])->first();

        if (!$event) $this->createEvent($info, $loc);
    }

    /**
     * @param $info
     * @return Location
     **/
    protected function createLocation($info)
    {
        // TODO: validate input types

        $loc = Location::create([
            'human_name' => $info['location']['human_name'],
        ]);

        if (isset($info['location']['human_street_address']))
            $loc->human_street_address = $info['location']['human_street_address'];

        if (isset($info['location']['lat'])) $loc->lat = $info['location']['lat'];
        if (isset($info['location']['lon'])) $loc->lon = $info['location']['lon'];
        if (isset($info['location']['url'])) $loc->url = $info['location']['url'];

        $loc->save();

        return $loc;
    }

    /**
     * @param $info
     * @param $loc Location
     **/
    protected function createEvent($info, $loc)
    {
        // TODO: validate input types

        $event = Event::create([
            'title'         => $info['title'],
            'description'   => $info['description'],
            'hash'          => $info['hash'],
            'starting_time' => Carbon::parse($info['starting_time']),
            'url'           => $info['url'],
        ]);

        if (isset($info['ending_time']))
            $event->ending_time = Carbon::parse($info['ending_time']);

        if (isset($info['notes']))
            $event->notes = $info['notes'];

        $event->location()->associate($loc);

        if (isset($info['tags'])) {
            // TODO: add tags
        }

        $event->save();
    }
}
