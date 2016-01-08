<template>
    <div class="event">
        <h2>{{ event.title }} @ {{ event.location.human_name }}</h2>

        <div class="row">
            <div class="col-xs-12 col-sm-8">
                <p>{{ event.description }}</p>
            </div>
            <div class="col-xs-12 col-sm-4">
                <a href="{{ event.url }}">Website</a>
                <br/>
                <br/>

                <strong>Beginnt:</strong>
                <time>{{ event.starting_time | dateformat 'LLL'}}</time>
                <br/>

                <template v-if="event.ending_time != null">
                    <br/>
                    <strong>Endet:</strong>
                    <br/>
                    <time>{{ event.ending_time | dateformat 'LLL'}}</time>
                    <br/>
                </template>

                <template v-if="event.location.human_street_address != null">
                    <br/>
                    <address v-html="event.location.human_street_address | nl2br"></address>
                </template>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <!-- TODO: fahrinfo -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            event: {
                required: true
            }
        }
    };
</script>
