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
                    @handle-export="exportExcel($event)"
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
import { index } from "@/api/business/phanloai";
import indexMixin from "@/mixins/crud/index";
import FileSaver from "file-saver";
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
                per_page: 20,
            },
            form: {
                id: undefined,
                ngay: new Date().toLocaleDateString("en-CA"),
                 khach_hang_id:null,
                nguoi_can:null,
                kho_id:null,
                so_phieu:null,
                phe_lieu_id:null,
                so_luong:null,
                noi_dung:null,
                chitiets:[]
            },
        };
    },
    methods: {
        exportExcel(e) {
           window.location.assign(
                `/api/phanloai/export?ngay[]=`+this.defaultParams.ngay[0]+'&ngay[]='+this.defaultParams.ngay[1]
            );
        },
    },
};
</script>
