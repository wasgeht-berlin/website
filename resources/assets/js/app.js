var Vue = require('vue');

Vue.use(require('vue-resource'));

var moment = require('moment');
moment.locale('de', require('moment/locale/de'));

Vue.config.debug = true;

Vue.filter('nl2br', function (str) {
    // based on nl2br by phpjs.org
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br />$2');
});

Vue.filter('dateformat', function (datestr, format) {
    return moment(datestr).format(format);
});

var vm = new Vue({
    el: 'body',

    data: {
        events: [],
        currentPage: 0,
        perPage: 15,
        pageCount: 0
    },

    ready: function () {
        // TODO: provide the events object as a navigable vue-resource
        this.$http.get('/data?action=events.' + this.perPage, function (res) {
            this.$set('events', res.data);
            this.$set('currentPage', res.current_page);
            this.$set('pageCount', res.last_page);
        });
    },

    components: {
        Event: require('./components/event.vue'),
        Month: require('./components/calendar/month.vue'),
        Map: require('./components/map.vue')
    }
});

//        'loadMore': function () {
//            if (this.currentPage < this.pageCount - 1) {
//                this.currentPage += 1;
//            }
//
//            this.$http.get('/data?action=events.' + this.perPage + '&page=' + this.currentPage, function (res) {
//                this.$set('events', res.data);
//                this.$set('currentPage', res.current_page);
//                this.$set('pageCount', res.last_page);
//
//                document.getElementById('eventList').scrollIntoView();
//            })
