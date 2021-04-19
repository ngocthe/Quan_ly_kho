<template>
    <v-row>
        <v-col md="12" cols="12" class="pb-0">
            <v-row>
                <v-col cols="12" class="pb-0">
                    <v-autocomplete
                    multiple
                        v-model="params.month"
                        :items="[
                            { id: 1, name: 1 },
                            { id: 2, name: 2 },
                            { id: 3, name: 3 },
                            { id: 4, name: 4 },
                            { id: 5, name: 5 },
                            { id: 6, name: 6 },
                            { id: 7, name: 7 },
                            { id: 8, name: 8 },
                            { id: 9, name: 9 },
                            { id: 10, name: 10 },
                            { id: 11, name: 11 },
                            { id: 12, name: 12 },
                        ]"
                        item-text="name"
                        item-value="id"
                        @change="$emit('handle-search')"
                        :label="$t('month')"
                        clearable
                        outlined
                        dense
                    ></v-autocomplete>
                </v-col>
                <v-col cols="12" class="pb-0">
                    <v-autocomplete
                        v-model="params.year"
                        :items="[
                            { id: 0, name: 2019 },
                            { id: 1, name: 2020 },
                            { id: 2, name: 2021 },
                            { id: 3, name: 2022 },
                            { id: 4, name: 2023 },
                            { id: 5, name: 2024 },
                            { id: 6, name: 2025 },
                            { id: 7, name: 2026 },
                        ]"
                        item-text="name"
                        @change="$emit('handle-search')"
                        item-value="name"
                        :label="$t('year')"
                        clearable
                        outlined
                        dense
                    ></v-autocomplete>
                </v-col>
                <v-col cols="12" class="pb-0">
                    <v-autocomplete
                        v-model="params.type"
                        :items="[
                            { id: 0, name: $t('debt_t') },
                            { id: 1, name: $t('debt_c') },
                        ]"
                        item-text="name"
                        item-value="id"
                        @change="$emit('handle-search')"
                        :label="$t('type_transaction')"
                        clearable
                        outlined
                        dense
                    ></v-autocomplete>
                </v-col>
                <v-col cols="12" class="pb-0">
                    <v-autocomplete
                        v-if="params.type === 1"
                        v-model="params.reference_id"
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
                        v-model="params.reference_id"
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
            </v-row>
        </v-col>
        <v-col cols="12" class="pb-0">
            <v-btn
                class="float-md-right mr-md-0"
                @click="$emit('handle-search')"
                color="primary"
                small
                >{{ $t("form_search_label") }}</v-btn
            >
            <v-btn
                class="float-md-right mr-4"
                @click="$emit('handle-reset')"
                color="primary"
                small
                >Reset</v-btn
            >
        </v-col>
    </v-row>
</template>
<script>
import DateRangePicker from "@/components/DateRangePicker";

export default {
    props: ["params", "options"],
    components: { DateRangePicker },
};
</script>
<style lang=""></style>
