@extends ('base')

@section ('content')
    <map :events="events" :lon="52.51704" :lat="13.38792"></map>

    <template id="map-template">
        {{--<div v-bind:style="style">--}}
            {{--<div id="map" style="position: absolute; top: 0; bottom: 0; width: 100%"></div>--}}
        {{--</div>--}}
        <div id="map" v-bind:style="mapStyle"></div>
    </template>
@stop
