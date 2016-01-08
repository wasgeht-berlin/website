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
}
