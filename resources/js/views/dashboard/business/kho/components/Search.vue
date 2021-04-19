<template>
    <v-row>
        <v-col md="8" cols="12" class="pb-2">
            <v-row>
                <v-col cols="12" md="6" lg="4" class="pb-0">
                    <DateRangePicker :value.sync="params.date"
                /></v-col>

                <v-col cols="12" md="6" lg="4" class="pb-0">
                    <v-text-field
                        :label="$t('form_search_label')"
                        :placeholder="$t('form_search_placeholder')"
                        clearable
                        outlined
                        hide-details
                        @keyup.enter="$emit('handle-search')"
                        dense
                        v-model="params.search"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" md="6" lg="4" class="pb-0">
                    <v-autocomplete
                        v-if="type === 1"
                        v-model="params.partner_id"
                        :items="options.suppliers"
                        item-text="name"
                        @change="$emit('handle-search')"
                        item-value="id"
                        :label="$t('partner')"
                        clearable
                        hide-details
                        outlined
                        dense
                    ></v-autocomplete>
                    <v-autocomplete
                        v-else
                        v-model="params.partner_id"
                        :items="options.customers"
                        item-text="name"
                        item-value="id"
                        hide-details
                        @change="$emit('handle-search')"
                        :label="$t('partner')"
                        clearable
                        outlined
                        dense
                    ></v-autocomplete>
                </v-col>
                <v-col cols="12" md="6" lg="4" class="pb-0">
                    <v-select
                        v-model="type"
                        :items="types"
                        item-text="label"
                        item-value="id"
                        dense
                        hide-details
                    ></v-select>
                </v-col>
                <v-col cols="12" md="6" lg="4" class="pb-0">
                    <v-select
                        v-model="params.type"
                        :items="notesType[type === 1 ? 'grn' : 'gdn']"
                        item-text="name"
                        item-value="id"
                        dense
                        @change="$emit('handle-search')"
                        :label="$t('type')"
                        hide-details
                    ></v-select>
                </v-col>
                <v-col cols="12" md="6" lg="4">
                    <v-btn
                        @click="$emit('handle-export')"
                        class="mx-2"
                        x-small
                        fab
                        dark
                        color="success"
                    >
                        <v-icon dark>mdi-download</v-icon>
                    </v-btn>
                </v-col>
            </v-row>
        </v-col>
        <v-col cols="12" md="4" style="margin-top: 12px">
            <v-btn
                class="float-md-right mr-md-0 mb-0 ml-4"
                style="height: 40px;width:0px"
                @click="$emit('handle-create')"
                dark
                medium
                color="primary"
            >
                <v-icon dark>mdi-plus</v-icon>
            </v-btn>
            <v-btn
                class="float-md-right mr-md-0 mb-0"
                @click="$emit('handle-search')"
                color="primary"
                style="height: 40px"
                medium
                >{{ $t("form_search_label") }}</v-btn
            >
            <v-btn
                class="float-md-right mr-4 mb-0"
                style="height: 40px"
                @click="$emit('handle-reset')"
                color="primary"
                >Reset</v-btn
            >
        </v-col>
    </v-row>
</template>
<script>
import DateRangePicker from "@/components/DateRangePicker";
export default {
    props: ["params", "options", "notesType"],
    components: { DateRangePicker },
    data() {
        return {
            type: 1
        };
    },
    watch: {
        type(val) {
            this.$emit("handle-change-type", val);
        }
    },
    computed: {
        types() {
            return [
                {
                    id: 1,
                    label: this.$t("grn")
                },
                {
                    id: 2,
                    label: this.$t("gdn")
                }
            ];
        }
    }
};
</script>
<style lang=""></style>
