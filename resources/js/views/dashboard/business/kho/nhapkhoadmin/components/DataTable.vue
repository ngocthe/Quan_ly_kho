<template>
    <v-data-table
        :headers="getHeaders()"
              v-model="selected"
        :items="tableData"
        disable-pagination
        fixed-header
              @input="enterSelect($event)"
        show-select
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
                v-if="selected.length>0"
                    @click="ghiSo()"
                    class="mx-2"
                    small
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-pencil</v-icon>
                    Ghi sổ
                </v-btn>
                     <v-btn
                    @click="$emit('handle-create');getSoPhieu()"
                    class="mx-2"
                    
                    small
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-plus</v-icon>
                    Thêm phiếu
                </v-btn>
             
            </v-toolbar>
        </template>

        <template v-slot:item.so_luong_chung_tu="{ item }">
              <v-text-field
                v-model="item.so_luong_chung_tu"
                @change="updateSL(item.id,item.so_luong_chung_tu,item.hang_gui)"
                type="number"
                :min="0"
                dense
            ></v-text-field>
        </template>
         <template v-slot:item.hang_gui="{ item }">
              <v-text-field
                v-model="item.hang_gui"
                @change="updateSL(item.id,item.so_luong_chung_tu,item.hang_gui)"
                type="number"
                :min="0"
                dense
            ></v-text-field>
        </template>
         
         <template v-slot:item.chenh_lech="{ item }">
            {{+item.so_luong_thuc_te - (item.so_luong_chung_tu?(parseFloat(item.so_luong_chung_tu)+(parseFloat(item.hang_gui))):0)}}
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
import { destroy ,updateSL,ghiso} from "@/api/business/nhapkho";
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
                    text: 'Phế liệu',
                    value: "phe_lieu",
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
                    text: 'Hàng gửi',
                    value: "hang_gui",
                },
                 {
                    text: 'Chênh lệch',
                    value: "chenh_lech",
                },
                    {
                    text: 'Kho',
                    value: "kho",
                },
              
                // {
                //     text: this.$t("actions"),
                //     value: "actions",
                //     align: "center",
                //     width: 120,
                // },
            ];
        },
    },
     data: () => ({
  
    selected: []}),
      methods:{
          
          async updateSL(id,so_luong_chung_tu,hang_gui){
               this.$loader(true);
              await updateSL(id,{so_luong_chung_tu:so_luong_chung_tu,hang_gui:hang_gui});
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
                this.form.kho_id = 19;
                   this.form.so_phieu = data.so_phieu;
            },
        toggleAll() {
            console.log(this.selected);
            if (this.selected.length) this.selected = []
            else this.selected = this.desserts.slice()
            },
    changeSort(column) {
            if (this.pagination.sortBy === column) {
                this.pagination.descending = !this.pagination.descending
            } else {
                this.pagination.sortBy = column
                this.pagination.descending = false
            }
    },
    enterSelect(values) {
        this.selected=values
      if (values.length == this.itemsPerPage) {
        alert('selected all')
      }
    },
    async  ghiSo(){
        await ghiso({data:this.selected})
         this.$snackbar(
                "Ghi sổ thành công" ,
                "success"
            );
        this.$emit('handle-ghiso')
    }
    }
};
</script>
