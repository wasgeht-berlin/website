<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends ElasticModel
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'starting_time',
        'ending_time',
        'hash',
        'notes',
        'url'
    ];

    protected $dates = ['starting_time', 'ending_time'];

    protected $hidden = ['notes', 'location_id'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'events_tags');
    }

    protected function toElasticArray()
    {
        $array = $this->toArray();

        // TODO: index tags

        return $array;
    }
}
