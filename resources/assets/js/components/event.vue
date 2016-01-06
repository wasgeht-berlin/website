<template>
    <div class="event">
        <h2>{{ event.title }} @ {{ event.location.human_name }}</h2>

        <div class="row">
            <div class="col-xs-12 col-sm-8">
                <p>{{ event.description }}</p>
            </div>
            <div class="col-xs-12 col-sm-4">
                <strong>Beginnt:</strong>
                <br />
                <br />
                <time>{{ event.starting_time | dateformat 'LLL'}}</time>
                <template v-if="event.ending_time != null">
                    <br />
                    <br />
                    <strong>Endet:</strong>
                    <br />
                    <br />
                    <time>{{ event.ending_time | dateformat 'LLL'}}</time>
                </template>
                <br />
                <br />
                <address v-html="event.location.human_street_address | nl2br"></address>
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
