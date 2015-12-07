var moment = require('moment');
moment.locale('de', require('moment/locale/de'));

var Vue = require('vue');
Vue.use(require('vue-resource'));
Vue.config.debug = true;

Vue.filter('nl2br', function (str) {
    // based on nl2br by phpjs.org
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br />$2');
});

Vue.filter('dateformat', function (datestr, format) {
   return moment(datestr).format(format);
});

//Vue.component('EventMap', {
//    template: '<div id="event-{{id}}-map" style="width: 300px; height: 150px;"></div>',
//
//    props: {
//        id: {
//            required: true
//        },
//        lat: {
//            required: true
//        },
//        lon: {
//            required: true
//        }
//    },
//
//    ready: function () {
//        var map = new mapboxgl.Map({
//            container: 'event-' + this.id + '-map',
//            style: 'mapbox://styles/mapbox/dark-v8',
//            center: [this.lon, this.lat],
//            zoom: 7
//        })
//    }
//});

Vue.component('Event', {
    template: '#event-template',

    props: {
        event: {
            required: true
        }
    }
});

new Vue({
    'el': 'body',

    data: {
        events: [],
        currentPage: 0,
        perPage: 15,
        pageCount: 0
    },

    ready: function() {
        this.$http.get('/data?action=events.'+this.perPage, function (res) {
            this.$set('events', res.data);
            this.$set('currentPage', res.current_page);
            this.$set('pageCount', res.last_page);
        });
    },

    methods: {
        'loadMore': function () {
            if (this.currentPage < this.pageCount - 1) {
                this.currentPage += 1;
            }

            this.$http.get('/data?action=events.'+this.perPage +'&page='+this.currentPage, function (res) {
                this.$set('events', res.data);
                this.$set('currentPage', res.current_page);
                this.$set('pageCount', res.last_page);

                document.getElementById('eventList').scrollIntoView();
            })
        }
    }
});
