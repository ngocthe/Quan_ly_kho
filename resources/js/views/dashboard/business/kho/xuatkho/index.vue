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
                    @push-detail="pushDetail()"
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
    </v-container>
</template>

<script>
import DataTable from "./components/DataTable";
import Search from "./components/Search";
import DialogForm from "./components/DialogForm";
import Pagination from "@/components/Pagination";
import { index } from "@/api/business/xuatkho";
import indexMixin from "@/mixins/crud/index";
import FileSaver from "file-saver";
import { index as getKhos } from "@/api/business/kho";
import { index as getPhelieus } from "@/api/business/phelieu";

import { index as getDoitacs } from "@/api/business/doitac";
import { index as getXes } from "@/api/business/xe";
import { index as getTaiKhoans } from "@/api/business/taikhoan";

export default {
  mixins: [
        indexMixin(index, {
            doitacs: {
                func: getDoitacs,
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
                kho_id:null,
                ngay: [
                    new Date(
                        new Date().getFullYear(),
                        new Date().getMonth()-1,1
                    ).toLocaleDateString("en-CA"),
                    new Date().toLocaleDateString("en-CA")
                ],
                doi_tac_id:null,
                xe_id:null,
                per_page: 20,
            },
            form: {
                id: undefined,
                ngay: new Date().toLocaleDateString("en-CA"),
                so_phieu: null,
                 doi_tac_id:null,
                 ly_do:null,
                xe_id:null,
                kho_id:null,
                tai_khoan_no_id:null,
                tai_khoan_co_id:null,
                chitiets:[]
            },
        };
    },
    methods: {
        pushDetail() {
            if(this.form.chitiets.length==0)
           this.form.chitiets.push({
                id: Math.random(),
                phe_lieu_id:null,
                dvt: 'Kg',
                so_luong_de_xuat: null,
                so_luong_thuc_te:null,
                don_gia: 0
            });
        },
    },
};
</script>
