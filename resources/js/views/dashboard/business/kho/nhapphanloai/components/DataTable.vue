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
                <v-text-field type="number" dense style="width:10px !important; margin-top:10px" v-model="so" label="Nhập số CT"></v-text-field>
                <v-btn
                    @click="$emit('handle-export',so)"
                    class="mx-2"
                    small
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-download</v-icon>
                    Xuất theo mẫu misa
                </v-btn>
            </v-toolbar>
        </template>
        <template slot="body.append">
                            <tr class="pink--text">
                                <th class="title">Totals</th>
                                <th class="title">{{ sumField('so_luong') }}</th>
                                <th class="title"></th>
                                <th class="title"></th>
                                <th class="title"></th>
                                   <th class="title"></th>
                                <th class="title"></th>
                                <th class="title"></th>

                            </tr>
                        </template>
        <template v-slot:item.actions="{ item }">
            <v-btn
            v-if="!item.duyet"
                x-small
                @click="duyet(item)"
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

        </template>
        <template>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
    </v-data-table>
</template>
<script>
import axios from "axios";
import { destroy } from "@/api/business/nhapkho";
import dataTableMixin from "@/mixins/crud/data-table";
export default {
    mixins: [dataTableMixin(destroy)],
     data() {
        return {
            so:0
        }},
    computed: {
  
        headers() {
            return [
                { text: "Phế liệu", value: "phe_lieu" },
                {
                    text: 'Số lượng',
                    value: "so_luong",
                },
                   {
                    text: 'Đơn vị',
                    value: "dvt",
                },
                 {
                    text: 'Khách hàng',
                    value: "khach_hang",
                },
                 {
                    text: 'Ngày',
                    value: "ngay",
                },
                    {
                    text: 'Kho xuất',
                    value: "kho",
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
    methods: {
        async duyet(item){
            console.log(item)
               await  axios.put(`/api/duyetpl/${item.id}`);
                this.$snackbar(
                "Duyệt thành công",
                    "success"
            );
                            this.$emit('handle-search2')

            }
            ,
            sumField(key) {
  // sum data in given key (property)
  let total = 0
  const sum = this.tableData.reduce((accumulator, currentValue) => {
    return (total += +currentValue[key])
  }, 0)
  return sum
},
  }
};
</script>
