<template>
    <v-main>
        <v-card flat class="my-container">
            <router-view />
        </v-card>

        <!-- <dashboard-core-footer /> -->
    </v-main>
</template>

<script>
export default {
    name: "DashboardCoreView",

    components: {
        DashboardCoreFooter: () => import("./Footer"),
    },
};
</script>
