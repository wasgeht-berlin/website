<template>
    <div class="calendar-day {{ todayClass }} {{ weekdayClass }} {{ monthClass }}"
         role="gridcell">
        <div class="calendar-day-title" aria-label="{{ day.format('dddd, DD.MM.YYYY') }}">
            {{ day.format('DD') }}
        </div>
        <div class="calendar-day-body">
            <ul>
                <template v-for="event in events">
                    <li >
                        <span class="calendar-event-time">{{ event.starting_time | dateformat 'hh:ss' }}</span>
                        <span class="calendar-event-title">{{ event.title }}</span>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</template>

<script>
    var moment = require('moment');
    moment.locale('de', require('moment/locale/de'));

    export default {
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
    };
</script>