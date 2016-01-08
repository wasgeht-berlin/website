<?php namespace App\Model\Transformers;

use App\Model\Event;
use League\Fractal\Resource\Item;

class EventTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['location'];

    public function transform(Event $event)
    {
        return [
            'id'            => $this->format($event->id),
            'title'         => $this->format($event->title),
            'description'   => $this->format($event->description),
            'starting_time' => $this->format($event->starting_time),
            'ending_time'   => $this->format($event->ending_time),
            'url'           => $this->format($event->url),
            'hash'          => $this->format($event->hash),
            'tags'          => $this->listRelation($event->tags, ['name'])
        ];
    }

    public function includeLocation(Event $event)
    {
        return new Item($event->location, new LocationTransformer(), 'location');
    }
}
