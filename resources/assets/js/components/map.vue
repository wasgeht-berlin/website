<template>
    <!--<div v-bind:style="style">-->
    <!--<div id="map" style="position: absolute; top: 0; bottom: 0; width: 100%"></div>-->
    <!--</div>-->
    <div id="map" v-bind:style="mapStyle"></div>
</template>

<script>
    var L = require('leaflet');

    export default {
        data() {
            return {
                'mapHeight': 300
            };
        },

        computed: {
            mapStyle() {
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

        ready() {
            var map = L.map('map').setView([this.lon, this.lat], 11);

            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
            }).addTo(map);

            this.mapHeight = window.innerHeight - this.$el.getBoundingClientRect().top - 100;
        }
    }
</script>
