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
                <v-toolbar-title>Danh sách các phiên đấu giá</v-toolbar-title>
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

              <v-btn
                    @click="$emit('handle-export')"
                    class="mx-2"
                    small
                    fab
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-download</v-icon>
                </v-btn> 
            </v-toolbar>
        </template>
         <template v-slot:item.trang_thai="{ item }">
         <v-chip v-if="item.trang_thai=='chua_dien_ra'" color="orange">Chưa diễn ra</v-chip>
          <v-chip v-if="item.trang_thai=='dang_dien_ra'" color="error">Đang diễn ra</v-chip>
         <v-chip v-if="item.trang_thai=='da_ket_thuc'" color="success">Kết thúc</v-chip>

         </template>
          <template v-slot:item.khach_hang_du_thau="{ item }">
             <v-btn
                x-small
                @click="$emit('handle-chitet', item)"
                dark
                color="primary"
            >
            Chi tiết
            </v-btn>
          </template>
        <template v-slot:item.actions="{ item }">
             
            <v-btn
                x-small
                @click="$emit('handle-edit', item)"
                dark
                color="primary"
            >
            Sửa
            </v-btn>
            <v-btn
                x-small
                @click="handleDelete(item.id)"
                class="ml-2"
                dark
                color="error"
            >
             Xoá
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
import { destroy } from "@/api/business/doitac";
import dataTableMixin from "@/mixins/crud/data-table";
export default {
    mixins: [dataTableMixin(destroy)],
    computed: {
        headers() {
            return [
                { text: 'Mã', value: "ma" },
                { text: 'Ngày bắt đầu', value: "bat_dau" },
                { text: 'Ngày kết thúc', value: "ket_thuc" },
                { text: 'Sản phẩm', value: "ten_san_pham" },
		         { text:'Giá khởi điểm', value: "gia_khoi_diem" },
                 { text:'Người trúng thầu', value: "ng" },
                 { text:'Trạng thái', value: "trang_thai" },
                 { text:'Khách hàng dự thầu', value: "khach_hang_du_thau" },

                {
                    text: this.$t("actions"),
                    value: "actions",
                    align: "center",
                    width:130
                },
            ];
        },
    },
};
</script>
