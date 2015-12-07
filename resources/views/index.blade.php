@extends ('base')

@section ('content')
    <div class="row">
        {{-- // TODO: search/filter --}}
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-3">
            <ul class="list-unstyled" id="eventList">
                <li v-for="e in events">
                    <event :event="e"></event>
                </li>
            </ul>
        </div>

        <div class="col-xs-12 col-md-6 col-md-offset-3" id="no-more-content" v-if="currentPage == lastPage">
            Keine weiteren Elemente verfügbar.
        </div>
        <template v-else>
            <div class="col-xs-12 col-md-6 col-md-offset-3 text-center" id="more">
                <button class="btn btn-lg" @click="loadMore()">Weitere Elemente laden</button>
            </div>
        </template>
    </div>

    <template id="event-template">
        <div class="event">
            <h2>@{{ event.title }} @ @{{ event.location.human_name }}</h2>

            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <p>@{{ event.description }}</p>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <strong>Beginnt:</strong>
                    <br />
                    <br />
                    <time>@{{ event.starting_time | dateformat 'LLL'}}</time>
                    <template v-if="event.ending_time != null">
                        <br />
                        <br />
                        <strong>Endet:</strong>
                        <br />
                        <br />
                        <time>@{{ event.ending_time | dateformat 'LLL'}}</time>
                    </template>
                    <br />
                    <br />
                    <address v-html="event.location.human_street_address | nl2br"></address>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    {{-- TODO: fahrinfo --}}
                </div>
            </div>
        </div>
    </template>
@stop