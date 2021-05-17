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
      
 
        <template v-slot:item.actions="{ item }">
            <v-btn
            v-if="!item.duyet_kho"
                x-small
                @click="duyetXuongHang(item)"
                color="primary"
            >
                <v-icon >mdi-check</v-icon>
                Duyệt

            </v-btn>
             <v-chip
                    v-else
                  class="ma-2"
                   color="orange"
                      text-color="white"
                    >
                
               Đã duyệt 

             </v-chip>
            <!-- <v-btn
                x-small
                @click="handleDelete(item.id)"
                class="ml-2"
                fab
                dark
                color="error"
            >
                <v-icon dark>mdi-delete</v-icon>
            </v-btn> -->
        </template>
       
        <template v-slot:no-data>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
    </v-data-table>
</template>
<script>
import { duyet } from "@/api/business/kho";
import axios from "axios";
import dataTableMixin from "@/mixins/crud/data-table";
export default {
    mixins: [dataTableMixin()],
    computed: {
        headers() {
            return [
                { text: 'Số phiếu', value: "id" },
                { text: 'Ngày', value: "ngay" },
                { text: 'Ca', value: "ca" },
		         { text:'Khách hàng', value: "ten_khach_hang" },
                 { text:'Xe', value: "xe.bien_kiem_soat" },
                  { text:'Người xuống hàng', value: "nguoi_xuong_hang" },
                 { text:'Khối lượng', value: "khoi_luong" },
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
      async duyetXuongHang(item){
                await  axios.put(`/api/xuong_hang/duyet/${item.id}`);
                this.$emit('handle-reset')
                this.$snackbar(
                "Duyệt thành công",
                    "success"
            );
            // try {
            //     await duyet(item.id);
            //     this.$snackbar(
            //     "Duyệt thành công",
            //         "success"
            // );
            // } catch (error) {
            //      console.log(error);
            //     this.loading = false;
            // }
        }
    }
};
</script>
