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
@stop