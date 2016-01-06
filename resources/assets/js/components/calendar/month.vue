<template>
    <div class="col-xs-12 col-md-10 col-md-offset-1">
        <div class="calendar-title-bar">
            <div class="row">
                <div class="col-xs-4 calendar-previous-month" role="button" @click="changeMonth(-1)">
                    <span class="glyphicon glyphicon-triangle-left"></span>
                    {{ previousMonth.format('MMMM') }}
                </div>
                <div class="col-xs-4 calendar-current-month">
                    <strong>{{ date.format('MMMM') }}</strong>
                    <br/>
                    {{ date.format('YYYY') }}
                </div>
                <div class="col-xs-4 calendar-next-month" role="button" @click="changeMonth(+1)">
                    {{ nextMonth.format('MMMM') }}
                    <span class="glyphicon glyphicon-triangle-right"></span>
                </div>
            </div>
        </div>
        <div class="calendar-sheet" role="grid">
            <template v-for="week in weeksInMonth">
                <div class="calendar-week" role="row">
                    <day v-for="dayInWeek in 7"
                         :day="day(week, dayInWeek)"
                         :events="eventsInDay(day(week, dayInWeek))"
                         :current-month="month">
                    </day>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    var moment = require('moment');
    moment.locale('de', require('moment/locale/de'));

    export default {
        data() {
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
            date() {
                return moment().year(this.year).month(this.month);
            },

            previousMonth() {
                return moment(this.date).subtract(1, 'M');
            },

            nextMonth() {
                return moment(this.date).add(1, 'M');
            },

            daysInMonth() {
                var currentMonth = this.date.month();

                var daysInMonth = 31;
                if (currentMonth % 2 == 1) {
                    if (currentMonth == 1) {
                        if (this.date.isLeapYear()) {
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

            currentDayInMonth() {
                return this.date.date();
            },

            weeksInMonth() {
                var weeksEstimate = Math.ceil(this.daysInMonth / 7) + 1;

                // remove the overflow week if the month ends on the last day of the week
                if (this.date.clone().day(0).add(1, 'M').subtract(1, 'd').weekday() == 6) {
                    weeksEstimate -= 1;
                }

                return weeksEstimate;
            },

            firstDayOfMonth() {
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
        },

        components: {
            Day: require('./day.vue')
        }
    };
</script>