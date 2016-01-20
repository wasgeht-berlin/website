<template>
    <input class="form-control" v-model="query" @change="performSearch"
           placeholder="Einträge filtern&hellip;"
           lazy/>
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
                this.$http.post('/api/v1/event/search', {
                    query: this.query
                }).then(function (result) {
                    if (result.ok) {
                        // data is in result.data
                    }
                }, function (result) {
                    // TODO: handle failed request
                });
            }
        }
    };
</script>