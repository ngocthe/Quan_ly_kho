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
                <v-toolbar-title>{{ $t("re_ex_list") }}</v-toolbar-title>
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
        <template v-slot:item.actions="{ item }">
            
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
        <template v-slot:item.type="{ item }">
            <v-chip v-if="item.type == 1" color="warning" dark>{{
                $t("type_in")
            }}</v-chip>
            <v-chip v-else color="success" dark>{{ $t("type_out") }}</v-chip>
        </template>
        <template v-slot:item.type_t="{ item }">
            <span v-if="item.type == 1 && item.type_t1 == 1">{{
                $t("type_t11")
            }}</span>
            <span v-if="item.type == 1 && item.type_t1 == 2">{{
                $t("type_t12")
            }}</span>
            <span v-if="item.type == 1 && item.type_t1 == 3">{{
                $t("type_t13")
            }}</span>
            <span v-if="item.type == 2 && item.type_c1 == 1">{{
                $t("type_c11")
            }}</span>
            <span v-if="item.type == 2 && item.type_c1 == 2">{{
                $t("type_c12")
            }}</span>
            <span v-if="item.type == 2 && item.type_c1 == 3">{{
                $t("type_c13")
            }}</span>
            <span v-if="item.type == 2 && item.type_c1 == 4">{{
                $t("type_c14")
            }}</span>
        </template>
        <template v-slot:item.unit_vnd="{ item }">
            {{ item.unit_vnd ? "VNĐ" : "USD" }}
        </template>
        <template v-slot:item.ck="{ item }">
            <v-chip v-if="item.ck" color="warning" dark>{{ $t("ck") }}</v-chip>
            <v-chip v-else color="success" dark>{{ $t("tm") }}</v-chip>
        </template>
        <template v-slot:item.active="{ item }">
            <v-chip v-if="item.active" color="success" dark>{{
                $t("active")
            }}</v-chip>
            <v-chip v-else color="warning" dark>{{ $t("deactive") }}</v-chip>

        </template>
            <template v-slot:item.amount="{ item }">
            {{ item.amount | money }}
        </template>
            <template v-slot:item.fee_ck="{ item }">
            {{ item.fee_ck | money }}
        </template>
        <template v-slot:item.total="{ item }">
            {{ (parseInt(item.fee_ck) + parseInt(item.amount)) | money }}
        </template>
            
        <template v-slot:no-data>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
    </v-data-table>
</template>
<script>
import { destroy } from "@/api/business/re-ex";
import dataTableMixin from "@/mixins/crud/data-table";
export default {
    mixins: [dataTableMixin(destroy)],
    computed: {
        types() {
            return [
                { id: 1, label: this.$t("grn") },
                { id: 2, label: this.$t("gdn") },
            ];
        },
        headers() {
            return [
                { text: this.$t("date"), value: "date" },

                {
                    text: this.$t("account_number"),
                    value: "bank.account_number",
                },
                { text: this.$t("type_transaction"), value: "type" },
                { text: this.$t("purpose"), value: "type_t" },
                {
                    text: this.$t("description_transaction"),
                    value: "description",
                },
                { text: this.$t("type_money"), value: "unit_vnd" },
               
                { text: this.$t("ck"), value: "ck" },
                 { text: this.$t("amount"), value: "amount" },
                  { text: this.$t("fee_ck"), value: "fee_ck" },
                     { text: 'Total', value: "total" },
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
