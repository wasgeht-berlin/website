<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Jobs\UpdateProviders;
use App\Model\Event;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function data(Request $request)
    {
        if ($request->has('action')) {
            $action = explode('.', $request->input('action'));

            switch ($action[0]) {
                default:
                    break;

                case 'events':
                    // NOTE: This will be replaced by a proper API once the client side is fully stubbed out
                    $numItems = 15;
                    if (isset($action[1]) && is_numeric($action[1])) {
                        $numItems = $action[1];
                    }

                    $events = Event::with(['location', 'tags'])->paginate($numItems);

                    return response()->json($events);
            }
        }

        return response()->json([]);
    }

    public function map()
    {
        return view('map');
    }

    public function calendar()
    {
        return view('calendar');
    }

    public function contribute(Filesystem $fs)
    {
        $markdown = $fs->get('providers/CONTRIBUTING.md');
        $markdown = preg_replace('/^.+\n/', '', $markdown);

        $html = \Parsedown::instance()->parse($markdown);

        return view('contribute', compact('html'));
    }

    public function about()
    {
        $markdown = file_get_contents(base_path('attribution.md'));

        $attribution = \Parsedown::instance()->parse($markdown);

        return view('about', compact('attribution'));
    }

    public function update(Request $request)
    {
        $result = 'Scheduled update.';

        switch ($request->header('x-github-event')) {
            case 'push':
                $this->dispatch(new UpdateProviders());
                break;

            case 'pull_request':
                $payload = json_decode($request->getContent(), true);

                if ($payload['action'] == 'closed' && $payload['merged']) {
                    $this->dispatch(new UpdateProviders());
                    break;
                }

                $result = 'No merge happened. Nothing to do.';
                break;

            case 'ping':
            default:
                $result = Inspiring::quote();
                break;
        }

        return response()->json(compact('result'));
    }

    public function api()
    {
        $event = [
            "data" => [
                "id"            => 2,
                "title"         => "Event Titel",
                "description"   => "Event Beschreibung",
                "starting_time" => "2016-01-09T23:00:00+0100",
                "ending_time"   => null,
                "url"           => "http://link-zum-eve.nt",
                "hash"          => "eindeutigesHash",
                "tags"          => [],
                "location"      => '<locationObject>',
                "type"          => "event",
            ],
        ];

        $location = [
            "data" => [
                "id"                   => 3,
                "human_name"           => "Lido",
                "human_street_address" => "Lido\nCuvrystraße 7\n10997 Berlin",
                "url"                  => "http://www.lido-berlin.de",
                "lat"                  => 52.4992474,
                "lon"                  => 13.4451043,
                "osm_feature_id"       => 360111407,
                "type"                 => "location",
            ]
        ];

        $pagination = ["meta" => [
            "pagination" => [
                "total"        => 20,
                "count"        => 10,
                "per_page"     => 10,
                "current_page" => 1,
                "total_pages"  => 2,
                "links"        => [
                    "next" => "link zum nächsten Element",
                ],
            ],
        ]];

        $eventExample = json_encode($event, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $locationExample = json_encode($location, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $paginationExample = json_encode($pagination, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('api', compact('eventExample', 'locationExample', 'paginationExample'));
    }
}
