<template>
    <div>
        <div class="col-xs-12 col-md-6 col-md-offset-3">
            <template v-if="events">
                <ul class="list-unstyled" id="eventList">
                    <li v-for="e in events.data">
                        <event :event="e"></event>
                    </li>
                </ul>
            </template>
        </div>

        <div class="col-xs-12 col-md-6">
            <button class="btn btn-lg" @click="back()" v-if="page > 1">Zurück</button>
        </div>
        <div class="col-xs-12 col-md-6">
            <button class="btn btn-lg" @click="forward()" v-if="page < maxPages">weiter</button>
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

        computed: {
            page() {
                if (!this.event) return 0;

                return this.event.meta.pagination.current_page;
            },

            pageCount() {
                if (!this.event) return 0;

                return this.event.meta.pagination.total_pages;
            }
        },

        methods: {
            forward() {
                if (this.page < this.pageCount) {
                    api.events.query({
                        page: this.page + 1,
                        order_by: 'starting_time',
                        starting_time_after: 'yesterday'
                    }).then(function (result) {
                        vm.$set('events', result.data);
                    });
                }
            },

            back() {
                if (this.page > 1) {
                    api.events.query({
                        page: this.page - 1,
                        order_by: 'starting_time',
                        starting_time_after: 'yesterday'
                    }).then(function (result) {
                        vm.$set('events', result.data);
                    });
                }
            }
        },

        components: {
            Event: require('./event.vue')
        }
    };
</script>