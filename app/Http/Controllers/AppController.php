<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Model\Event;
use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $events = Event::take(10)->get();

        return view('index', compact('events'));
    }

    public function data(Request $request)
    {
        if ($request->has('action')) {
            $action = explode('.', $request->input('action'));

            switch ($action[0]) {
                default:
                    break;

                case 'events':
                    $numItems = 15;
                    if (isset($action[1]) && is_numeric($action[1])) {
                        $numItems = $action[1];
                    }

                    $events = Event::with('location')->paginate($numItems);

                    return response()->json($events);

//                    $skip = 0;
//                    if (isset($action[1]) && is_numeric($action[1]))
//                    {
//                        $skip = $action[1];
//                    }
//
//                    $get = 10;
//                    if (isset($action[2]) && is_numeric($action[2]))
//                    {
//                        $get = $action[2];
//                    }
//
//                    return Event::with('location')->skip($skip)->take($get)->get();
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

    public function contribute(GitHubManager $gh)
    {
        $contributing_md = \Cache::remember('app.contribute.md', 10, function () use ($gh) {
            return $gh->repo()
                ->contents()
                ->download('wasgeht-berlin', 'data-providers', 'CONTRIBUTING.md');
        });

        $markdown = \Parsedown::instance()->parse($contributing_md);

        // FIXME: remove first heading level

        return view('contribute', compact('markdown'));
    }

    public function about()
    {
        return view('about');
    }

    public function gh($repository)
    {
        // TODO: github webhook for autoupdating the data-providers
        // NOTE: should extract this thing as a library from the oparl spec website
    }
}
