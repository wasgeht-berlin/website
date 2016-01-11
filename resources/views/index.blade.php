@extends ('base')

@section ('content')
    <div class="row">
        <filter :events.sync="events"></filter>
    </div>
    <div class="row">
        <event-list :events="events"></event-list>
    </div>
@stop