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
                prepend-inner-icon="mdi-calendar"
                readonly
                :label="label"
                v-bind="attrs"
                outlined
                :clearable="clearable"
                dense
                hide-details
                v-on="on"
            ></v-text-field>
        </template>
        <v-date-picker
            v-model="dateLocal"
            range
            @input="handleInput"
        ></v-date-picker>
    </v-menu>
</template>
<script>
export default {
    props: ["value", "clearable", "label"],
    data: () => ({
        menu: false,
        dateLocal: null
    }),
    computed: {
        dateRangeText: {
            set(val) {
                this.dateLocal = [];
                this.$emit("update:value", []);
                this.$emit("change");
            },
            get() {
                if (this.value.length === 0) return "";
                return this.value
                    .map(item => new Date(item).toLocaleDateString("en-GB"))
                    .join("  -  ");
            }
        }
    },
    watch: {
        menu(val) {
            if (!val) {
                if (this.dateLocal.length === 1) {
                    this.dateLocal = [this.dateLocal[0], this.dateLocal[0]];
                    this.$emit("update:value", [
                        this.dateLocal[0],
                        this.dateLocal[0]
                    ]);
                }
            }
        },
        dateLocal(val) {
            if (val[0] > val[1]) {
                this.dateLocal = val.sort();
            }
            if (val.length > 1) {
                this.menu = false;
                this.$emit("update:value", val);
                this.$emit("change");
            }
        }
    },
    methods: {
        handleInput(value) {}
    },
    mounted() {
        this.dateLocal = this.value;
    }
};
</script>
