@extends ('base')

@section ('content')
    <div>
        Nicht vergessen: Es ist möglich, dass nicht alle
        verfügbaren Ereignisse auf der Karte angezeigt werden.
    </div>

    <map :events="events" :lon="52.51704" :lat="13.38792"></map>
@stop
