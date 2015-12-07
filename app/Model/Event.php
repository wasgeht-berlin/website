<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'description', 'starting_time', 'ending_time'];
    protected $dates = ['starting_time', 'ending_time'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
