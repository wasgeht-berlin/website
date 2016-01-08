<?php namespace App\Http\Controllers\API;

use App\Http\Requests\APIRequest;
use App\Model\Location;
use App\Model\Transformers\LocationTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class LocationController extends APIController
{
    public function index(APIRequest $request)
    {
        $limit = 20;
        if ($request->has('limit')) $limit = $request->input('limit');

        $locations = Location::paginate($limit);

        $resource = new Collection($locations, new LocationTransformer(), 'location');

        return $this->fractal->createData($resource)->toArray();
    }

    public function get(APIRequest $request, $id)
    {
        $location = Location::findOrFail($id);

        $resource = new Item($location, new LocationTransformer(), 'location');

        return $this->fractal->createData($resource)->toArray();
    }
}