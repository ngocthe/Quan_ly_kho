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
import { index } from "@/api/business/kho";
import indexMixin from "@/mixins/crud/index";
import FileSaver from "file-saver";
import { index as getNvbhs } from "@/api/business/nvbh";
import { index as getPhelieus } from "@/api/business/phelieu";
import { index as getThukhos } from "@/api/business/thukho";
export default {
  mixins: [
        indexMixin(index, {
            nvbhs: {
                func: getNvbhs,
                params: {
                    all: true,
                },
            },
            thukhos: {
                func: getThukhos,
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
        }),
    ],
    components: { DataTable, Search, DialogForm, Pagination },
    data() {
        return {
            defaultParams: {
                search: "",
                page: 1,
                thu_kho_id:null,
                 nhan_vien_ban_hang_id:null,
                per_page: 50,
            },
            form: {
                id: undefined,
                ma: "",
                ten: "",
                dia_chi:"",
                thu_kho_id:null,
                nhan_vien_ban_hang_id:null,
                chitiets:[]
            },
        };
    },
    methods: {
        // exportDataReport() {
        //     this.to(
        //         `/reports/export?date[]=${this.defaultParams.date[0]}&date[]=${this.defaultParams.date[1]}`
        //     );
        // },
    },
};
</script>
