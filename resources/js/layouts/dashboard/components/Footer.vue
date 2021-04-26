<template>
    <v-footer id="dashboard-core-footer" class="pt-0 pb-0">
        <v-container fluid>
            <v-row align="center" no-gutters>
                <v-col cols="12" md="auto">
                    <div
                        class="body-1 font-weight-light pt-6 pt-md-0 text-center"
                    >
                        Quick Start Template by AD
                    </div>
                </v-col>
                <v-spacer class="hidden-sm-and-down" />
                <v-col cols="12" md="auto">
                    <div
                        class="body-1 font-weight-light pt-6 pt-md-0 text-center"
                    >
                        <span class="font-weight-bold"
                            >Copyright &copy; {{ new Date().getFullYear() }} QUẢN LÝ KHO.</span
                        >
                        All rights reserved.
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </v-footer>
</template>

<script>
export default {
    name: "DashboardCoreFooter",

    data: () => ({
        links: [
            // {
            //     href: "#",
            //     text: "Creative Tim"
            // },
            // {
            //     href: "#",
            //     text: "About Us"
            // },
            // {
            //     href: "#",
            //     text: "Blog"
            // },
            // {
            //     href: "#",
            //     text: "Licenses"
            // }
        ]
    })
};
</script>

<style lang="sass">
#dashboard-core-footer
  a
    font-size: .825rem
    font-weight: 500
    text-decoration: none
    text-transform: uppercase
</style>
