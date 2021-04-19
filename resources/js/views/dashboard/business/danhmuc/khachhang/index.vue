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
import { index } from "@/api/business/khachhang";
import indexMixin from "@/mixins/crud/index";
import FileSaver from "file-saver";
export default {
    mixins: [indexMixin(index)],
    components: { DataTable, Search, DialogForm, Pagination },
    data() {
        return {
            defaultParams: {
                search: ""
            },
            form: {
                id: undefined,
                ma: "",
                ma_misa: "",
                ten: "",
                sdt: "",
                email:"",
                dia_chi: "",

            },
        };
    },
};
</script>
