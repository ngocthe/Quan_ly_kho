<template>
    <v-btn
        v-scroll="onScroll"
        fab
        dark
        fixed
        bottom
        right
        v-show="fab"
        color="primary"
        class="btn-scroll"
        @click="toTop"
        small
    >
        <v-icon small>mdi-arrow-up</v-icon>
    </v-btn>
</template>
<script>
export default {
    data: () => ({
        fab: false,
    }),

    methods: {
        onScroll(e) {
            if (typeof window === "undefined") return;
            const top = window.pageYOffset || e.target.scrollTop || 0;
            this.fab = top > 20;
        },
        toTop() {
            this.$vuetify.goTo(0);
        },
    },
};
</script>
<style lang="scss">
.btn-scroll {
    position: fixed;
    bottom: 100px;
    right: 200px;
    z-index: 9999999;
}
</style>