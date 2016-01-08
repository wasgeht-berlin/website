@extends ('base')

@section ('content')
    <div class="row">
        {{-- // TODO: search/filter --}}
    </div>
    <div class="row">
        <event-list :events="events"></event-list>
    </div>
@stop