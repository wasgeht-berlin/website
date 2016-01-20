<template>
    <input class="form-control" v-model="query" @change="performSearch"
           placeholder="Einträge filtern&hellip;"
           lazy/>
</template>

<script>
    import api from '../api.js'

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
                    vm.$set('events', result.data);
                }, function () {
                    api.events.query({}).then(function(result) {
                        vm.$set('events', result.data)
                    });
                });
            }
        }
    };
</script>