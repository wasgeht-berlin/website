var moment = require('moment');
moment.locale('de', require('moment/locale/de'));

var L = require('leaflet');

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

Vue.component('Event', {
    template: '#event-template',

    props: {
        event: {
            required: true
        }
    }
});

Vue.component('Calendar', {
    template: '#calendar-template',

    data: function () {
        var today = moment();

        return {
            'month': today.month(),
            'year': today.year()
        }
    },

    props: {
        'events': {
            required: true
        }
    },

    computed: {
        'date': function () {
            return moment().year(this.year).month(this.month);
        },

        'daysInMonth': function () {
            var currentMonth = this.date.month();

            var daysInMonth = 31;
            if (currentMonth % 2 == 1) {
                if (currentMonth == 1) {
                    if (this.date.isLeapYear() % 4 == 0) {
                        daysInMonth = 29;
                    } else {
                        daysInMonth = 28;
                    }
                } else {
                    daysInMonth = 30;
                }
            }

            return daysInMonth;
        },

        'currentDayInMonth': function () {
            return this.date.date();
        },

        'weeksInMonth': function () {
            var weeksEstimate = Math.ceil(this.daysInMonth / 7) + 1;
            // TODO: check if the first day of the last week
            // => (first + (estimate * 7) - 7) is still in the same month
            return weeksEstimate;
        },

        'firstDayOfMonth': function () {
            return moment().month(this.date.month()).date(1).day();
        }
    },

    methods: {
        'moment': function (date) {
            return moment(date);
        },

        'day': function (week, dayInWeek) {
            var offset = (week * 7)
                       + (dayInWeek - this.firstDayOfMonth)
                       - this.currentDayInMonth;

            return moment(this.date).add({days: offset});
        },

        'eventsInDay': function (day) {
            var events = [];

            for (event in this.events) {
                if (this.events.hasOwnProperty(event)
                    && moment(this.events[event].starting_time).isSame(day, 'day')) {
                    events.push(this.events[event]);
                }
            }

            return events;
        },

        'changeMonth': function (offset) {
            this.month += offset;
        }
    }
});

Vue.component('CalendarDay', {
    template: '#calendar-day-template',

    props: {
        'day': {
            required: true
        },

        'events': {
            required: true
        },

        'currentMonth': {
            required: true
        }
    },

    computed: {
        'todayClass': function () {
            if (this.isToday()) {
                return 'calendar-day-today';
            } else {
                return '';
            }
        },

        'weekdayClass': function () {
            return 'calendar-day-' + this.day.format('dd').toLowerCase()
        },

        'monthClass': function () {
            var month = 'current';

            if (this.day.month() < this.currentMonth) {
                month = 'previous';
            }

            if (this.day.month() > this.currentMonth) {
                month = 'next';
            }

            return 'calendar-day-' + month + '-month';
        }
    },

    methods: {
        'isToday': function () {
            return this.day.isSame(moment(), 'day');
        }
    }
});

Vue.component('Map', {
    template: '#map-template',

    data: function () {
        return {
            'mapHeight': 300
        };
    },

    computed: {
        'mapStyle': function() {
            return 'height: ' + this.mapHeight + 'px;' +
                   'position: relative; top: 0';
        }
    },

    props: {
        lat: {
            required: true
        },
        lon: {
            required: true
        }
    },

    ready: function () {
        var map = L.map('map').setView([this.lon, this.lat], 11);

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
        }).addTo(map);

        this.mapHeight = window.innerHeight - this.$el.getBoundingClientRect().top - 100;
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

    ready: function () {
        this.$http.get('/data?action=events.' + this.perPage, function (res) {
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

            this.$http.get('/data?action=events.' + this.perPage + '&page=' + this.currentPage, function (res) {
                this.$set('events', res.data);
                this.$set('currentPage', res.current_page);
                this.$set('pageCount', res.last_page);

                document.getElementById('eventList').scrollIntoView();
            })
        }
    }
});
