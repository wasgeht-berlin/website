<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'lat',
        'lon',
        'human_name',
        'human_street_address',
        'url',
    ];

    protected $visible = [
        'id',
        'lat',
        'lon',
        'human_name',
        'human_street_address',
        'url',
        'osm_feature_id'
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
