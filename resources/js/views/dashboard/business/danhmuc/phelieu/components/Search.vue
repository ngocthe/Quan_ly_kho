<template>
    <v-row>
        <v-col cols="12" class="pb-0">
            <v-row>
                <v-col cols="12" class="pb-0">
                    <DateRangePicker :value.sync="params.date"
                /></v-col>
                <v-col cols="12" class="pb-0">
                    <v-text-field
                        :label="$t('form_search_label')"
                        :placeholder="$t('form_search_placeholder')"
                        clearable
                        outlined
                        dense
                        v-model="params.search"
                    ></v-text-field>
                </v-col>
                <v-col cols="12" class="pb-0">
                    <v-autocomplete
                        v-model="params.bank_id"
                        :items="options.banks"
                        item-text="account_number"
                        item-value="id"
                        :label="$t('account_number')"
                        clearable
                        outlined
                        dense
                    ></v-autocomplete>
                </v-col>
                <v-col cols="12" class="pb-0">
                    <v-autocomplete
                        v-model="params.type"
                        :items="[
                            { id: 1, name: $t('type_in') },
                            { id: 2, name: $t('type_out') },
                        ]"
                        item-text="name"
                        item-value="id"
                        :label="$t('type_transaction')"
                        clearable
                        outlined
                        dense
                    ></v-autocomplete>
                </v-col>
                <v-col cols="12" class="pb-0">
                    <a
                        :href="`/api/reports/export?date[]=${this.params.date[0]}&date[]=${this.params.date[1]}`"
                        target="_blank"
                        class="mx-2"
                        x-small
                        fab
                        dark
                        color="indigo"
                        style="
                            text-decoration: none;
                            color: white;
                            background: green;
                            padding: 10px;
                            border-radius: 10px;
                        "
                    >
                        Export report
                    </a>
                </v-col>
                <v-col cols="12" class="pb-0">
                    <v-btn
                        class="float-md-right"
                        @click="$emit('handle-search')"
                        dense
                        color="primary"
                        style="width: 90px"
                        >{{ $t("form_search_label") }}</v-btn
                    >
                    <v-btn
                        class="float-md-right"
                        @click="$emit('handle-reset')"
                        color="primary"
                        style="width: 80px"
                        dense
                        >Reset</v-btn
                    >
                </v-col>
            </v-row>
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
