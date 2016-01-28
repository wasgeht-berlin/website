<template>
    <div>
        <div class="col-xs-12 col-md-8 col-md-offset-2">

            <filter :events.sync="events"></filter>

            <list-navigation :events="events"></list-navigation>

            <div v-if="events && events.data">
                <table class="table table-condensed table-striped">
                    <tr v-for="event in events.data">
                        <td>
                            <a :href.once="event.url">{{ event.title }}</a>
                        </td>
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
            <list-navigation :events="events"></list-navigation>
        </div>
    </div>
</template>

<script>
    import Filter from '../filter.vue'
    import ListNavigation from './nav.vue'

    export default {
        props: {
            events: {
                required: true
            }
        },

        components: {
            Filter,
            ListNavigation
        }
    };
</script>