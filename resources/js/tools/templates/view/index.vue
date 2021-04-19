<template>
    <v-container
        style="height: calc(100vh - 60px); overflow-y: auto"
        fluid
        class="py-0"
    >
        <v-row>
            <v-col class="py-0" cols="12">
                <Search
                    :params="params"
                    @handle-search="getData(1)"
                    @handle-reset="reset"
                    :options="options"
                />
            </v-col>
            <v-col class="py-0" cols="12">
                <DataTable
                    :form="form"
                    :table-data="tableData"
                    @handle-edit="showDialogForm('edit', $event)"
                    @handle-create="showDialogForm('create')"
                    @handle-delete="getData()"
                    :pagination="pagination"
                    @handle-export="exportData"
                />
            </v-col>
            <v-col cols="12" class="py-0">
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
import { index } from "@/api/business/__model__";
import indexMixin from "@/mixins/crud/index";
export default {
    mixins: [indexMixin(index)],
    components: { DataTable, Search, DialogForm, Pagination },
    data() {
        return {
            defaultParams: {
                search: "",
                page: 1,
                per_page: 15,
                role_id: 0,
            },
            form: {
                id: undefined,
            },
        };
    },
};
</script>
