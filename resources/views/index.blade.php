@extends ('base')

@section ('content')
    <div class="row">
        <event-list :events.sync="events"></event-list>
    </div>
@stop