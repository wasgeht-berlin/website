<?php namespace App\Model;

use Cocur\Slugify\Slugify;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'description', 'filename'];

    public static function boot()
    {
        static::creating(function (Tag $tag) {
            $slugify = Slugify::create();

            $tag->slug = $slugify->slugify($tag->name);
        });
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'events_tags');
    }
}
