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
                <v-toolbar-title>{{ $t("debt_list") }}</v-toolbar-title>
                <v-spacer></v-spacer>

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
 <template v-slot:item.amount_owed_usd="{ item }">
            {{ (item.type_money=='$'?item.amount_owed:null)|money}}
        </template>
            <template v-slot:item.amount_owed_vnd="{ item }">
            {{ item.amount_owed * item.exchange_rate|money}}
        </template>
          
        <template v-slot:item.amount_payment="{ item }">
            {{ item.amount_payment  |money}}
        </template>
             <template v-slot:item.amount_owed_exist="{ item }">
            {{ item.amount_owed *item.exchange_rate - item.amount_payment |money}}
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
import { destroy } from "@/api/business/khachhang";
import dataTableMixin from "@/mixins/crud/data-table";
export default {
    mixins: [dataTableMixin(destroy)],
    computed: {
        headers() {
            return [
                { text: this.$t("date_hd"), value: "date_hd" },
                { text: this.$t("number_hd"), value: "number_hd" },
                { text: this.$t("partner"), value: "reference.name" },
                { text: this.$t("duration"), value: "duration" },
                { text: this.$t("type_money"), value: "type_money" },
		 { text: this.$t("amount_owed") +' (USD)', value: "amount_owed_usd" },
                { text: this.$t("amount_owed") +' (VNĐ)', value: "amount_owed_vnd" },

                { text: this.$t("amount_payment"), value: "amount_payment" },
                { text: this.$t("date_payment"), value: "date_payment" },
                {
                    text: this.$t("amount_owed_exist"),
                    value: "amount_owed_exist",
                },
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
