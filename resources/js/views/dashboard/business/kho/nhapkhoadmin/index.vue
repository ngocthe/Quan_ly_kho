<template>
    <v-container fluid>
        <v-row>
            <v-col class="pb-0" cols="12">
                <Search
                    :params="params"
                    @khang_df="khachDf($event)"
                    @handle-search="getData(1)"
                    @handle-reset="reset"
                    :options="options"
                   @handle-export="exportExcel($event)"

                />
            </v-col>
            <v-col class="pt-0" cols="12">
                <DataTable
                    :form="form"
                    :table-data="tableData"
                    @handle-edit="showDialogForm('edit', $event)"
                    @handle-create="showDialogForm2('create')"
                    @handle-delete="getData()"           
                     @handle-ghiso="getData()"
                     @push-detail="pushDetail()"
                    @handle-export="exportData"
                />
            </v-col>
           
        </v-row>
        <DialogForm
            @created="getData(1);showDialog=false"
            @updated="getData"
            :params="params"
            :options="options"
            :show-dialog.sync="showDialog"
            :editing="editing"
            :form="form"
        />
    </v-container>
</template>

<script>
import DataTable from "./components/DataTable";
import Search from "./components/Search";
import DialogForm from "./components/DialogForm";
import Pagination from "@/components/Pagination";
import { index_admin as index } from "@/api/business/nhapkho";
import indexMixin from "@/mixins/crud/index";
import FileSaver from "file-saver";
import { index as getKhos } from "@/api/business/kho";
import { index as getPhelieus } from "@/api/business/phelieu";

import { index as getKhachhangs } from "@/api/business/khachhang";
import { index as getXes } from "@/api/business/xe";
import { index as getTaiKhoans } from "@/api/business/taikhoan";

export default {
  mixins: [
        indexMixin(index, {
            khachhangs: {
                func: getKhachhangs,
                params: {
                    all: true,
                },
            },
            phelieus: {
                func: getPhelieus,
                params: {
                    all: true,
                },
            },
            khos: {
                func: getKhos,
                params: {
                   all: true,
                },
            },
              xes: {
                func: getXes,
                params: {
                   all: true,
                },
            },
             tknos: {
                func: getTaiKhoans,
                params: {
                      all: true,
                   loai: 2,
                },
            },
             tkcos: {
                func: getTaiKhoans,
                params: {
                   loai: 1,
                },
            },
        }),
    ],
    components: { DataTable, Search, DialogForm, Pagination },
    data() {
        return {
            defaultParams: {
                search: "",
                page: 1,
                ghi_so:0,
                kho_id:null,
                ngay: [
                     new Date().toLocaleDateString("en-CA"),
                    new Date().toLocaleDateString("en-CA")
                ],
                khach_hang_id:null,
                xe_id:null,
                per_page: 20,
            },

            form: {
                id: undefined,
                ngay: new Date(),
                ca: 1,
                 khach_hang_id:null,
                xe_id:null,
                kho_id:19,
                so_phieu:null,
                tai_khoan_no_id:null,
                tai_khoan_co_id:null,
                phe_lieu_id:null,
                dvt:null,
                so_luong_thuc_te:0,
                so_luong_chung_tu:0,
                hang_gui:0,
                hang_cong:0,
                chitiets:[]
            },
             formDef: {
                id: undefined,
                ngay: new Date(),
                ca: 1,
                 khach_hang_id:null,
                xe_id:null,
                kho_id:19,
                so_phieu:null,
                tai_khoan_no_id:null,
                tai_khoan_co_id:null,
                phe_lieu_id:null,
                dvt:null,
                so_luong_thuc_te:0,
                so_luong_chung_tu:0,
                hang_gui:0,
                hang_cong:0,
                chitiets:[]
            },
        };
    },
    methods: {
        khachDf(val){
         this.form.khach_hang_id=val
        },
         showDialogForm2(mode) {
            
                this.form = this.formDef;
                this.form.id = undefined;
                this.editing = false;
                this.form.khach_hang_id=this.params.khach_hang_id
                    this.showDialog = true;
        },
         pushDetail() {
            if(this.form.chitiets.length==0)
           this.form.chitiets.push({
                id: Math.random(),
                phe_lieu_id:null,
                dvt: 'Kg',
                so_luong_thuc_te: null,
                so_luong_chung_tu:0,
                hang_gui:0,
                hang_cong:0,
                don_gia: 0
            });
        },
         exportExcel(e) {
           window.location.assign(
                `/api/nhapKhoAdmin/?export=true&ngay[]=`+this.params.ngay[0]+'&ngay[]='+this.params.ngay[1]+'&so='+e
            );
        },
    },
   
};
</script>
