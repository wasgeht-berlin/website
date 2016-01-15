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

var api = {
    events : Vue.resource('/api/v1/event'),
    locations : Vue.resource('/api/v1/location')
};

var vm = new Vue({
    el: 'body',

    data: {
        events: null
    },

    ready: function () {
        api.events.query().then(function (result) {
            vm.$set('events', result.data);
        })
    },

    components: {
        Filter: require('./components/filter.vue'),
        EventList: require('./components/events/list.vue'),
        Month: require('./components/calendar/month.vue'),
        Map: require('./components/map.vue')
    }
});
