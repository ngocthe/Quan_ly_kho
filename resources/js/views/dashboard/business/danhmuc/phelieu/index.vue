<template>
    <v-container fluid>
        <v-row>
            <v-col class="pb-0" md="9" xl="10" cols="12">
                <DataTable
                    :form="form"
                    :table-data="tableData"
                    @handle-edit="showDialogForm('edit', $event)"
                    @handle-create="showDialogForm('create')"
                    @handle-delete="getData()"
                    @handle-export="exportData()"
                />
            </v-col>
            <v-col class="py-0" md="3" xl="2" cols="12">
                <Search
                    :params="params"
                    @handle-search="getData(1)"
                    @handle-reset="reset"
                    :options="options"
                    @handle-export="exportDataReport()"
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
import { index } from "@/api/business/phelieu";
import indexMixin from "@/mixins/crud/index";
import FileSaver from "file-saver";
export default {
    mixins: [
        indexMixin(index)
    ],
    components: { DataTable, Search, DialogForm, Pagination },
    data() {
        return {
            defaultParams: {
                search: "",
                page: 1,
                per_page: 15,
                bank_id: "",
                type: null,
                date: [
                    new Date(
                        new Date().getFullYear(),
                        new Date().getMonth(),
                        1
                    ).toLocaleDateString("en-CA"),
                    new Date().toLocaleDateString("en-CA"),
                ],
            },
            form: {
                id: undefined,
                bank_id: "",
                date: new Date().toLocaleDateString("en-CA"),
                type: 1,
                type_t2: null,
                type_t1: null,
                type_c2: null,
                type_c1: null,
                unit_vnd: 1,
                month:1,
                year:2021,
                vat: 0,
                amount: 0,
                ck: 1,
                description: "",
                fee_ck:0,
            },
        };
    },
    methods: {
        exportDataReport() {
            this.to(
                `/reports/export?date[]=${this.defaultParams.date[0]}&date[]=${this.defaultParams.date[1]}`
            );
        },
    },
};
</script>
