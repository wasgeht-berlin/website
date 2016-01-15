<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use League\Fractal\Manager;

class APIController extends Controller
{
    protected $fractal = null;

    public function __construct()
    {
        $this->fractal = new Manager();

        $this->fractal->setSerializer(new Serializer());
    }

    /**
     * @param \App\Http\Requests\APIRequest $request
     * @return array|mixed|string
     **/
    protected function getItemsPerPage(APIRequest $request)
    {
        $limit = (($request->has('limit'))) ? $request->input('limit') : config('api.items_per_page.default');

        return $limit;
    }

    /**
     * @param \App\Http\Requests\APIRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     **/
    protected function processDateFilters(APIRequest $request, Builder $query)
    {
        if ($request->has('starting_time_after')) {
            $query = $query->where('starting_time', '>', Carbon::parse($request->input('starting_time_after')));

            return $query;
        }

        return $query;
    }

    /**
     * @param \App\Http\Requests\APIRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     **/
    protected function processOrdering(APIRequest $request, Builder $query)
    {
        if ($request->has('order_by')) {
            $query->orderBy($request->input('order_by'), $request->input('sort', 'asc'));
        }
    }
}