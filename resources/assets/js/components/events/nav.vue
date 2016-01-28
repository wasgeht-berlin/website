<template>
    <nav class="event-list" v-show="pageCount > 1">
        <ul class="pagination pagination-sm">
            <li :class="{ disabled: currentPage <= 1 }">
                <a v-on:click.prevent="back($event)">{{ labels.front }}</a>
            </li>

            <template v-if="pageCount <= frontElements + windowElements + backElements" >
                <nav-element
                        v-for="page in pageCount"
                        :page="page"
                        :current-page="currentPage">
                </nav-element>
            </template>
            <template v-else>
                <nav-element
                        v-for="page in frontElements"
                        :page="page"
                        :current-page="currentPage"
                >
                </nav-element>

                <li class="disabled" v-if="currentPage > frontElements + windowElements - 1">
                    <a href="#">&hellip;</a>
                </li>

                <nav-element
                        v-for="page in windowElements"
                        :page="page"
                        :current-page="currentPage"
                        :offset="windowOffset">
                </nav-element>

                <li class="disabled" v-if="currentPage < pageCount - backElements - (windowElements / 2)">
                    <a href="#">&hellip;</a>
                </li>

                <nav-element
                        v-for="page in backElements"
                        :page="page"
                        :current-page="currentPage"
                        :offset="pageCount - backElements">
                </nav-element>
            </template>

            <li :class="{ disabled: currentPage == pageCount }">
                <a v-on:click.prevent="forward($event)">{{ labels.back }}</a>
            </li>
        </ul>
    </nav>
</template>

<script>
    import NavElement from './nav-element.vue'

    export default {
        props: {
            events: {
                required: true
            },
            frontElements: {
                type: Number,
                default: 3
            },
            windowElements: {
                type: Number,
                default: 4
            },
            backElements: {
                type: Number,
                default: 3
            },
            labels: {
                type: Object,
                default() {
                    return {
                        front: 'Neuere',
                        back: 'Ältere'
                    }
                }
            }
        },

        computed: {
            currentPage() {
                if (!this.events) return 0;
                return this.events.meta.pagination.current_page;
            },

            pageCount() {
                if (!this.events) return 0;
                return this.events.meta.pagination.total_pages;
            },

            windowOffset() {
                if (this.currentPage <= this.frontElements + (this.windowElements / 2)) {
                    return this.frontElements;
                } else {
                    // NOTE: This is more of an "good-for-now" kind of solution and will not work forever
                    return this.frontElements + (this.windowElements / 2);
                }
            }
        },

        methods: {
            back(event) {
                if (event) {
                    this.$dispatch('navigate-back');
                }
            },

            forward(event) {
                if (event) {
                    this.$dispatch('navigate-forward');
                }
            }
        },

        components: {
            NavElement
        }
    }
</script>
