<template>
    <div>
        <div class="col-xs-12 col-md-6 col-md-offset-3">

            <filter :events.sync="events"></filter>

            <div v-if="events && events.data">
                <table class="table table-condensed table-striped">
                    <tr v-for="event in events.data">
                        <td>{{ event.title }}</td>
                        <td>
                        <span v-if="event.location">
                            {{ event.location.human_name }}
                        </span>
                            <span v-else class="location-unknown">(Keine Ortsangabe)</span>
                        </td>
                        <td>{{ event.starting_time | dateformat 'LLL' }}</td>
                        <td>
                            <span v-if="event.ending_time">
                                {{ event.ending_time | dateformat 'LLL' }}
                            </span>
                            <span v-else class="ending-time-unknown">-</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div v-else>
                <span class="text-center">Keine Ereignisse gefunden.</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            events: {
                required: true
            }
        },

        components: {
            Filter: require('../filter.vue')
        }
    };
</script>