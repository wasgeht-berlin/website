<template>
    <div class="col-xs-12 col-sm-6 col-sm-offset-3">
        <input class="form-control" v-model="query" @change="performSearch"
               placeholder="Einträge filtern&hellip;"
               lazy/>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                query: ''
            }
        },

        props: {
            'events': {
                required: true,
                twoWay: true
            }
        },

        methods: {
            performSearch() {
                this.$http.get('/api/v1/event/search', {
                    query: this.query
                }).then(function (result) {
                    if (result.data.data && result.data.data.length > 0) {
                        // TODO: get result data into the view
                        // NOTE: more importantly, why is everything undefined in here?
                    }
                });
            }
        }
    };
</script>