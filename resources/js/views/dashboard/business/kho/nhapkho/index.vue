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
                    @handle-delete="getData()"
                     @push-detail="pushDetail()"
                 @handle-upload="a()"
                    @handle-export2="exportData2"
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
        @closeupload="closeupload()"
            :showupload="showupload"
            :editing="editing"
            :form="form"
            :options="options"

        />
    </v-container>
</template>

<script>
import DataTable from "./components/DataTable";
import Search from "./components/Search";
import DialogForm from "./components/DialogForm";
import DialogForm2 from "./components/DialogForm2";
import Pagination from "@/components/Pagination";
import { index } from "@/api/business/nhapkho";
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
    components: {DialogForm2, DataTable, Search, DialogForm, Pagination },
    data() {
        return {
            showupload:false,
            defaultParams: {
                search: "",
                page: 1,
                kho_id:null,
                ngay: [
                    new Date(
                        new Date().getFullYear(),
                        new Date().getMonth()-1,1
                    ).toLocaleDateString("en-CA"),
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
                kho_id:null,
                so_phieu:null,
                tai_khoan_no_id:null,
                tai_khoan_co_id:null,
                chitiets:[
                ]
            },
        };
    },
    methods: {
        a(){
            this.showupload=true;

        },
        closeupload(){
               this.showupload=false;
               this.getData()
         },
        exportData2() {
           window.location.assign(
                `/api/nhapkho/export?ngay[]=`+this.params.ngay[0]+'&ngay[]='+this.params.ngay[1]
            );
        },
         showDialogForm2(mode, id = null) {
            if (mode == "edit") {
                // this.editing = true;
                // for (let field in this.form) {
                //     this.form[field] = data[field];
                // }
            } else {
                this.form = JSON.parse(JSON.stringify(this.formDef));
                this.form.id = undefined;
                this.editing = false;
            }
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
                don_gia: 0,
                phanloais:[{
                     id: Math.random(),
                     phe_lieu_id:null,
                        dvt: 'Kg',
                        so_luong: null
                }
                ]

            });
        },
    },
};
</script>
