<template>
    <v-container fluid>
        <v-row>
            <v-col class="pb-0" cols="12">
                <Search
                    :params="params"
                    @handle-search="getData(1)"
                    @handle-reset="reset"
                    :options="options"
                />
            </v-col>
            <v-col class="pt-0" cols="12">
                <DataTable
                    :form="form"
                    :table-data="tableData"
                    @handle-edit="showDialogForm('edit', $event)"
                    @handle-create="showDialogForm('create')"
                    @handle-chitet="showDialogForm2($event)"
                    @handle-delete="getData()"
                    @handle-export="exportData"
                />
            </v-col>
             <v-col cols="12">
                <Pagination
                    :length="pagination.last_page"
                    :params="params"
                    @handle-change-page="getData"
                    @handle-change-per-page="getData(1)"
                />
            </v-col>
        </v-row>
        <DialogForm
            @created="getData(1)"
            @updated="getData"
            :options="options"
            :show-dialog.sync="showDialog"
            :editing="editing"
            :form="form"
        />
         <DialogForm2
            @created="getData(1)"
            @updated="getData"
            :options="options"
            :showdialog2.sync="showdialog2"
            :editing="editing"
            :idc="idc"
            :chitiets="cts"
        />
    </v-container>
</template>

<script>
import DataTable from "./components/DataTable";
import Search from "./components/Search";
import DialogForm from "./components/DialogForm";
import DialogForm2 from "./components/DialogForm2";
import Pagination from "@/components/Pagination";
import { index } from "@/api/business/doitac";
import { index as getKhachHangs  } from "@/api/business/khachhang";
import indexMixin from "@/mixins/crud/index";
import FileSaver from "file-saver";
export default {
  
    mixins: [
        indexMixin(index, {
        khachhangs : {
                func: getKhachHangs,
                params: {
                    all: true,
                },
            }
            }
        ),
    ],
    components: { DataTable,DialogForm2, Search, DialogForm, Pagination },
    data() {
        return {
            idc:null,
            showdialog2:false,
            defaultParams: {
                search: "",
                       page: 1,
                per_page: 50,
            },
            cts:[],
            form: {
                id: undefined,
                ma: "",
                ma_san_pham: "",
                ten_san_pham: "",
                hinh_anhs: [],
                bat_dau:new Date().toLocaleDateString("en-CA"),
                ket_thuc:new Date().toLocaleDateString("en-CA"),
                gia_khoi_diem: 0,
                    mo_ta:"",
                chi_tiet:"",
                so_luong_ban:"",
                dvt:"Vnđ/kg"

            },
        };
    },
    methods:{
        showDialogForm2(e){
            this.cts =e.chitiets
            this.idc =e.id
            this.showdialog2=true
        }
    }
};
</script>
