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
                <v-toolbar-title>Danh sách</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn
                    @click="$emit('handle-create'); getSoPhieu()"
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
                @click="$emit('handle-edit', item);"
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
        <template>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
    </v-data-table>
</template>
<script>
import { destroy } from "@/api/business/xuatkho";
import dataTableMixin from "@/mixins/crud/data-table";
import { getsophieu} from "@/api/business/kho";

export default {
    mixins: [dataTableMixin(destroy)],
    computed: {

        headers() {
            return [
                { text: "Ngày", value: "ngay" },
                {
                    text: 'Số phiếu',
                    value: "so_phieu",
                },
                 {
                    text: 'Từ kho',
                    value: "tu_kho.ten",
                },
                 {
                    text: 'Đến kho',
                    value: "den_kho.ten",
                },
                    {
                    text: 'Người tạo phiếu',
                    value: "nguoi_tao.name",
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
    methods:{
         async getSoPhieu() {
                this.$emit('push-detail')

                const { data } = await getsophieu('nhapkho');
                console.log(data)
                this.form.kho_id = data.kho_id;
                   this.form.so_phieu = data.so_phieu;
                },

    }
};
</script>
