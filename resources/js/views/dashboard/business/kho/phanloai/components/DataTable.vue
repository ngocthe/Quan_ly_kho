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
                    @click="$emit('handle-create');getSoPhieu()"
                    class="mx-2"
                    small
                    fab
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-plus</v-icon>
                </v-btn>
                <v-btn
                    @click="$emit('handle-export',item)"
                    class="mx-2"
                    small
                    fab
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-download</v-icon>
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
                <template v-slot:item.so_luong_dau_ra="{ item }">
                    {{item.chitiets.reduce((total, arg) => total + (+arg.so_luong), 0)}}
                </template>
                    <template v-slot:item.tieu_hao="{ item }">
                    {{item.so_luong - (+item.chitiets.reduce((total, arg) => total + (+arg.so_luong), 0))}}
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
        <template>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
    </v-data-table>
</template>
<script>
import { destroy } from "@/api/business/phanloai";
import dataTableMixin from "@/mixins/crud/data-table";
import { getsophieu} from "@/api/business/kho";

export default {
    mixins: [dataTableMixin(destroy)],
    computed: {

        headers() {
            return [
                { text: "Ngày", value: "ngay" },
                {
                    text: 'Ca',
                    value: "ca",
                },
                 {
                    text: 'Khách hàng',
                    value: "khach_hang.ten",
                },
                 {
                    text: 'Phiếu liệu xuất',
                    value: "phe_lieu.ma",
                },
                  {
                    text: 'Số lượng xuất',
                    value: "so_luong",
                },{
                    text:'Số lượng đầu ra',
                    value:'so_luong_dau_ra'
                },
                {
                    text:'Tiêu hao',
                    value:'tieu_hao'
                },
                    {
                    text: 'Kho',
                    value: "kho.ten",
                },
               
                {
                    text: this.$t("actions"),
                    value: "actions",
                    align: "center"
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

                }
    }
};
</script>
