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
import { index } from "@/api/business/nvbh";
import {index as getKhos } from "@/api/business/kho";

import indexMixin from "@/mixins/crud/index";
import FileSaver from "file-saver";
export default {
    mixins: [
        indexMixin(index, {
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
                per_page: 50,
            },
            form: {
                id: undefined,
                ten: "",
                sdt: "",
                email:"",
                cmnd:"",
                kho_id:null,
            },
        };
    },
};
</script>
