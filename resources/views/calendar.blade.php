@extends ('base')

@section ('content')
    <calendar :events="events"></calendar>

    <template id="calendar-template">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <div class="calendar-title-bar">
                <div class="row">
                    <div class="col-xs-4 calendar-previous-month" role="button" @click="changeMonth(-1)">
                        <span class="glyphicon glyphicon-triangle-left"></span>
                        @{{ moment(date).subtract(1, 'M').format('MMMM') }}
                    </div>
                    <div class="col-xs-4 calendar-current-month">
                        <strong>@{{ date.format('MMMM') }}</strong>
                        <br />
                        @{{ date.format('YYYY') }}
                    </div>
                    <div class="col-xs-4 calendar-next-month" role="button" @click="changeMonth(+1)">
                        @{{ moment(date).add(1, 'M').format('MMMM') }}
                        <span class="glyphicon glyphicon-triangle-right"></span>
                    </div>
                </div>
            </div>
            <div class="calendar-sheet" role="grid">
                    <template v-for="week in weeksInMonth">
                        <div class="calendar-week" role="row">
                            <calendar-day v-for="dayInWeek in 7"
                                          :day="day(week, dayInWeek)"
                                          :events="eventsInDay(day(week, dayInWeek))"
                                          :current-month="month">
                            </calendar-day>
                        </div>
                    </template>
            </div>
        </div>
    </template>

    <template id="calendar-day-template">
        <div class="calendar-day @{{ todayClass }} @{{ weekdayClass }} @{{ monthClass }}"
            role="gridcell">
            <div class="calendar-day-title" aria-label="@{{ day.format('dddd, DD.MM.YYYY') }}">
                @{{ day.format('DD') }}
            </div>
            <div class="calendar-day-body">
                <ul>
                    <li v-for="event in events">@{{ event.title }}</li>
                </ul>
            </div>
        </div>
    </template>
@stop
