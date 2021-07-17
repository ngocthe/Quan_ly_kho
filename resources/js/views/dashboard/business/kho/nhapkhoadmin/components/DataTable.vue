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
        <template v-slot:item.so_luong_chung_tu="{ item }">
              <v-text-field
                v-model="item.so_luong_chung_tu"
                @change="updateSL(item.id,item.so_luong_chung_tu)"
                type="number"
                :min="0"
                dense
            ></v-text-field>
        </template>
         <template v-slot:item.chenh_lech="{ item }">
            {{+item.so_luong_thuc_te - (item.so_luong_chung_tu?item.so_luong_chung_tu:0)}}
        </template>
        <template v-slot:item.actions="{ item }">
             <v-btn
                x-small
                @click="$emit('handle-edit', item)"
                fab
                dark
                color="primary"
            >
                <v-icon dark>mdi-check</v-icon>
            </v-btn>
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
        <template>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
    </v-data-table>
</template>
<script>
import { destroy ,updateSL} from "@/api/business/nhapkho";
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
                    value: "khach_hang",
                },
                 {
                    text: 'Xe',
                    value: "bks",
                },
                    {
                    text: 'ĐVT',
                    value: "dvt",
                },
                    {
                    text: 'SL thực tế',
                    value: "so_luong_thuc_te",
                },
                  {
                    text: 'SL biên bản',
                    value: "so_luong_chung_tu",
                },
                 {
                    text: 'Chênh lệch',
                    value: "chenh_lech",
                },
                    {
                    text: 'Kho',
                    value: "kho.ten",
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
          
          updateSL(id,so_luong_chung_tu){
               this.$loader(true);
              await updateSL(id,{so_luong_chung_tu:so_luong_chung_tu});
               this.$loader(false);
            this.$snackbar(
                this.editing ? "Cập nhật thành công" : "Thêm mới thành công",
                "success"
            );
          },
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
