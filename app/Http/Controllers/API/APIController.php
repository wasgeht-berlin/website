<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIRequest;
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
}