<?php namespace App\Http\Controllers\API;

use App\Http\Requests\APIRequest;
use App\Model\Event;
use App\Model\Transformers\EventTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class EventController extends APIController
{
    public function index(APIRequest $request)
    {
        $limit = $this->getItemsPerPage($request);

        $events = Event::with(['location', 'tags'])->paginate($limit);

        $resource = new Collection($events->items(), new EventTransformer(), 'event');
        $resource->setPaginator(new IlluminatePaginatorAdapter($events));

        return $this->fractal->createData($resource)->toArray();
    }

    public function get(APIRequest $request, $id)
    {
        $event = Event::findOrFail($id);

        $resource = new Item($event, new EventTransformer(), 'event');

        return $this->fractal->createData($resource)->toArray();
    }

    public function search(APIRequest $request)
    {
        if (!$request->has('query')) return response(json_encode([
            'error' => 'The /search endpoint requires a query parameter',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), 400);

        $results = Event::elasticFind($request->input('query'));

        $resource = new Collection($results, new EventTransformer(), 'event');

        // TODO: pagination

        return $this->fractal->createData($resource)->toArray();
    }
}
