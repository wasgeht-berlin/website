<?php namespace App\Http\Controllers\API;

use App\Http\Requests\APIRequest;
use App\Model\Location;
use App\Model\Transformers\LocationTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class LocationController extends APIController
{
    public function index(APIRequest $request)
    {
        $limit = $this->getItemsPerPage($request);

        $locations = Location::query();

        $locations = $this->processDateFilters($request, $locations);
        $this->processOrdering($request, $locations);

        $locations = $locations->paginate($limit);

        $resource = new Collection($locations->items(), new LocationTransformer(), 'location');
        $resource->setPaginator(new IlluminatePaginatorAdapter($locations));

        return $this->fractal->createData($resource)->toArray();
    }

    public function get(APIRequest $request, $id)
    {
        $location = Location::findOrFail($id);

        $resource = new Item($location, new LocationTransformer(), 'location');

        return $this->fractal->createData($resource)->toArray();
    }
}