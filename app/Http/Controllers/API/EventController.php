<?php namespace App\Http\Controllers\API;

use App\Http\Requests\APIRequest;
use App\Model\Event;
use App\Model\Transformers\EventTransformer;
use Illuminate\Foundation\Console\IlluminateCaster;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class EventController extends APIController
{
    public function index(APIRequest $request)
    {
        $limit = 20;
        if ($request->has('limit')) $limit = $request->input('limit');

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
}
