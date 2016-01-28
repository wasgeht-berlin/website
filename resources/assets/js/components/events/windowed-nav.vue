<template>
    <!--<template v-if="currentPage < frontElements">-->
        <!--<nav-element-->
                <!--v-for="page in windowElements"-->
                <!--:page="page"-->
                <!--:current-page="currentPage">-->
        <!--</nav-element>-->

        <!--<li class="disabled"><a href="#">&hellip;</a></li>-->

        <!--<nav-element-->
                <!--v-for="page in backElements"-->
                <!--:page="page"-->
                <!--:current-page="currentPage"-->
                <!--:offset="pageCount - backElements">-->
        <!--</nav-element>-->
    <!--</template>-->
    <!--<template v-else>-->
        <!--<template v-if="frontElements <= currentPage && currentPage < pageCount - backElements">-->
            <!--<nav-element-->
                    <!--v-for="page in frontElements"-->
                    <!--:page="page"-->
                    <!--:current-page="currentPage">-->
            <!--</nav-element>-->

            <!--<li class="disabled" v-if="currentPage > frontElements + 1"><a href="#">&hellip;</a></li>-->

            <!--<nav-element-->
                    <!--v-for="page in windowElements"-->
                    <!--:page="page"-->
                    <!--:current-page="currentPage"-->
                    <!--:offset="windowOffset">-->
            <!--</nav-element>-->

            <!--<li class="disabled" v-if="currentPage + windowElements < pageCount - backElements + 1">-->
                <!--<a href="#">&hellip;</a>-->
            <!--</li>-->

            <!--<nav-element-->
                    <!--v-for="page in backElements"-->
                    <!--:page="page"-->
                    <!--:current-page="currentPage"-->
                    <!--:offset="pageCount - backElements">-->
            <!--</nav-element>-->
        <!--</template>-->
        <!--<template v-else>-->
            <!--<nav-element-->
                    <!--v-for="page in frontElements"-->
                    <!--:page="page"-->
                    <!--:current-page="currentPage">-->
            <!--</nav-element>-->

            <!--<li class="disabled"><a href="#">&hellip;</a></li>-->

            <!--<nav-element-->
                    <!--v-for="page in windowElements"-->
                    <!--:page="page"-->
                    <!--:current-page="currentPage"-->
                    <!--:offset="pageCount - backElements">-->
            <!--</nav-element>-->
        <!--</template>-->
    <!--</template>-->
    <li>Windowed pagination.</li>
</template>

<script>
    import NavElement from './nav-element.vue'

    export default {
        props: {
            currentPage: { required: true },
            pageCount: { required: true },
            frontElements: {default: 3},
            windowElements: {default: 4},
            backElements: {default: 3}
        },

        methods: {
            windowOffset() {
                if (this.currentPage > this.frontElements) {
                    var offset = this.frontElements;

                    if (this.currentPage > this.frontElements + 1
                            && this.currentPage < this.pageCount - this.backElements)
                    {
                        var multiplier = 0;

                        if (this.currentPage >= (2 * this.windowElements)) {
                            //offset += (this.currentPage % this.windowElements);
                            multiplier = (this.currentPage - this.frontElements) / this.windowElements;
                        }

                        offset += (multiplier * this.windowElements) + (this.currentPage % this.windowElements);
                    }

                    return offset;
                } else {
                    return this.frontElements;
                }
            }
        },

        components: {
            NavElement
        }
    }
</script>