<template>
    <li :class="{ active: page + offset + 1 == currentPage }">
        <a v-on:click.prevent="select(page + 1, $event)" href="?page={{ page + offset + 1 }}">
            {{ page + offset + 1 }}
            <span class="sr-only" v-show="page == currentPage">(Aktuelle Seite)</span>
        </a>
    </li>
</template>

<script>
    export default {
        props: {
            page: { required: true },
            currentPage: { required: true },
            offset: { default: 0 }
        },

        methods: {
            select(page, event) {
                if (event) {
                    this.$dispatch('navigate-to', page);
                }
            }
        }
    };
</script>