<template>
    <v-menu
        v-model="menu"
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        min-width="290px"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-model="dateRangeText"
                :label="label || 'Date'"
                dense
                readonly
                v-bind="attrs"
                :clearable="clearable"
                :hide-details="hideDetails"
                v-on="on"
            ></v-text-field>
        </template>
        <v-date-picker
            v-model="dateLocal"
            @input="menu = false"
        ></v-date-picker>
    </v-menu>
</template>
<script>
export default {
    props: ["value", "clearable", "label", "hideDetails"],
    data: () => ({
        menu: false,
        dateLocal: null
    }),
    watch: {
        dateLocal(val) {
            this.$emit("updatevalue", val);
            this.$emit("change");
        },
        value(val){
             this.dateLocal = this.value;
        }
    },
    computed: {
        dateRangeText: {
            set(val) {
                this.dateLocal = "";
            },
            get() {
                console.log(1212)
                if (!this.value) return "";
                return new Date(this.value).toLocaleDateString("en-GB");
            }
        }
    },
    mounted() {
    }
};
</script>
