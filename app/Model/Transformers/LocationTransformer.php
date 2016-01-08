<?php namespace App\Model\Transformers;

use App\Model\Location;

class LocationTransformer extends BaseTransformer
{
    public function transform(Location $location)
    {
        return [
            'id' => $this->format($location->id),

            'human_name'           => $this->format($location->human_name),
            'human_street_address' => $this->format($location->human_street_address),
            'url'                  => $this->format($location->url),

            'lat' => $this->format($location->lat),
            'lon' => $this->format($location->lon),

            'osm_feature_id' => $this->format($location->osm_feature_id),
        ];
    }
}