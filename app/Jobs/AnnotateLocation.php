<?php namespace App\Jobs;

use App\Model\Location;
use Guzzle\Http\Client;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;

class AnnotateLocation extends Job implements SelfHandling
{
    use SerializesModels;

    protected $location = null;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function handle()
    {
        $client = new Client('http://overpass-api.de');

        // TODO: extend this query to find more venues
        $query = sprintf('[out:json];node["name"="%s"]["addr:city"="Berlin"];out;', $this->location->human_name);
        $url = "/api/interpreter?data=" . urlencode($query);

        $request = $client->get($url);
        $response = $request->send();

        $data = $response->json();

        if (count($data['elements']) > 0) {
            $info = $data['elements'][0];

            if (isset($info['tags']['addr:street'])
                && isset($info['tags']['addr:housenumber'])
                && isset($info['tags']['addr:postcode'])
            ) {
                $this->location->human_street_address = sprintf(
                    "%s\n%s %s\n%s Berlin",
                    $this->location->human_name,
                    $info['tags']['addr:street'],
                    $info['tags']['addr:housenumber'],
                    $info['tags']['addr:postcode']
                );
            }

            if (isset($info['tags']['contact:website'])) {
                $this->location->url = $info['tags']['contact:website'];
            }

            $this->location->osm_feature_id = $info['id'];

            $this->location->lat = $info['lat'];
            $this->location->lon = $info['lon'];

            $this->location->save();
        }
    }
}
