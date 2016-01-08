<?php namespace App\Http\Controllers\API;

use League\Fractal\Serializer\DataArraySerializer;

class Serializer extends DataArraySerializer
{
    public function item($resourceKey, array $data)
    {
        $data = $this->reformatData($data, $resourceKey);

        return parent::item($resourceKey, $data);
    }

    public function collection($resourceKey, array $data)
    {
        $data = collect($data)->map(function ($data) use ($resourceKey) {
            $data = $this->reformatData($data, $resourceKey);

            return $data;
        })->toArray();

        return parent::collection($resourceKey, $data);
    }

    protected function reformatData($data, $resourceKey)
    {
        $data['type'] = $resourceKey;

        if ($resourceKey == 'event' && isset($data['location']['data']))
            $data['location'] = $data['location']['data'];

        return $data;
    }
}
