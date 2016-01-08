<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use League\Fractal\Manager;

class APIController extends Controller
{
    protected $fractal = null;

    public function __construct()
    {
        $this->fractal = new Manager();

        $this->fractal->setSerializer(new Serializer());
    }
}