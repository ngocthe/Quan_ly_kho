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
                    @handle-search2="getData()"
                    @handle-create="showDialogForm('create')"
                    @handle-delete="getData()"
                    @handle-export="exportExcel($event)"
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
import { nhapphanloai as index } from "@/api/business/lichsu";
import indexMixin from "@/mixins/crud/index";
import { index as getKhos } from "@/api/business/kho";
import { index as getPhelieus } from "@/api/business/phelieu";

import { index as getKhachhangs } from "@/api/business/khachhang";

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

        }),
    ],
    components: { DataTable, Search, DialogForm, Pagination },
    data() {
        return {
            defaultParams: {
                loai:[],
                ngay: [
                   new Date().toLocaleDateString("en-CA"),
                    new Date().toLocaleDateString("en-CA")
                ],
                khach_hang_id:null,
                phe_lieu_id:null,
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
                chitiets:[]
            },
        };
    },
    methods: {
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
          exportExcel(e) {
           window.location.assign(
                `/api/nhapphanloais/?export=true&ngay[]=`+this.params.ngay[0]+'&ngay[]='+this.params.ngay[1]+'&so='+e
            );
        },
        // exportDataReport() {
        //     this.to(
        //         `/reports/export?date[]=${this.defaultParams.date[0]}&date[]=${this.defaultParams.date[1]}`
        //     );
        // },
    },
};
</script>
