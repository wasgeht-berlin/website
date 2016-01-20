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

import api from './api.js'

import EventList from './components/events/list.vue'
import Month from './components/calendar/month.vue'
import Map from './components/map.vue'

window.vm = new Vue({
    el: 'body',

    data: {
        events: null
    },

    ready() {
        api.events.query({
            order_by: 'starting_time',
            starting_time_after: 'yesterday'
        }).then(function (result) {
            vm.$set('events', result.data);
        });
    },

    components: {
        EventList,
        Month,
        Map
    }
});
