<template>
    <v-data-table
        :headers="getHeaders()"
        :items="tableData"
        disable-pagination
        fixed-header
        disable-sort
        calculate-widths
        hide-default-footer
        disable-filtering
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar class="custom-toolbar" flat>
                <v-toolbar-title>{{ $t("bank_list") }}</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn
                    @click="$emit('handle-create')"
                    class="mx-2"
                    small
                    fab
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-plus</v-icon>
                </v-btn>
                <!-- <v-btn
                    @click="$emit('handle-export')"
                    class="mx-2"
                    small
                    fab
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-download</v-icon>
                </v-btn> -->
            </v-toolbar>
        </template>
        <template v-slot:item.incurred="{ item }">
            {{ item.incurred | money }}
        </template>
        <template v-slot:item.balance="{ item }">
            {{ item.balance | money }}
        </template>
        <template v-slot:item.actions="{ item }">
            <v-btn
                x-small
                @click="$emit('handle-edit', item)"
                fab
                dark
                color="primary"
            >
                <v-icon dark>mdi-pencil</v-icon>
            </v-btn>
            <v-btn
                x-small
                @click="handleDelete(item.id)"
                class="ml-2"
                fab
                dark
                color="error"
            >
                <v-icon dark>mdi-delete</v-icon>
            </v-btn>
        </template>
        <template v-slot:item.active="{ item }">
            <v-chip v-if="item.active" color="success" dark>{{
                $t("active")
            }}</v-chip>
            <v-chip v-else color="warning" dark>{{ $t("deactive") }}</v-chip>
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
    </v-data-table>
</template>
<script>
import { destroy } from "@/api/business/xe";
import dataTableMixin from "@/mixins/crud/data-table";
export default {
    mixins: [dataTableMixin(destroy)],
    computed: {
        headers() {
            return [
                { text: this.$t("name"), value: "name" },
                { text: this.$t("code"), value: "code" },
                { text: this.$t("account_number"), value: "account_number" },
                { text: this.$t("unit"), value: "unit" },

                { text: this.$t("incurred"), value: "incurred" },
                { text: this.$t("balance"), value: "balance" },
                {
                    text: this.$t("actions"),
                    value: "actions",
                    align: "center",
                    width: 120,
                },
            ];
        },
    },
};
</script>
