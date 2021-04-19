<template>
    <v-data-table
        :headers="getHeaders()"
        :items="tableData"
        disable-pagination
        fixed-header
        disable-sort
        calculate-widths
        height="calc(100vh - 254px)"
        hide-default-footer
        disable-filtering
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar dense class="custom-toolbar" flat>
                <v-toolbar-title style="font-size: 15px"
                    >Total: {{ pagination.total }}</v-toolbar-title
                >
                <v-spacer></v-spacer>
                <v-btn
                    @click="$emit('handle-create')"
                    class="mx-2"
                    x-small
                    fab
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-plus</v-icon>
                </v-btn>
                <v-btn
                    @click="$emit('handle-export')"
                    class="mx-2"
                    x-small
                    fab
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-download</v-icon>
                </v-btn>
            </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-btn
                x-small
                @click="$emit('handle-edit', item)"
                fab
                dark
                color="primary"
            >
                <v-icon dark>mdi-pencil</v-icon>
            </v-btn>
            <v-btn
                x-small
                @click="handleDelete(item.id)"
                class="ml-2"
                fab
                dark
                color="error"
            >
                <v-icon dark>mdi-delete</v-icon>
            </v-btn>
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
    </v-data-table>
</template>
<script>
import { destroy } from "@/api/business/__model__";
import dataTableMixin from "@/mixins/crud/data-table";
export default {
    mixins: [dataTableMixin(destroy)],
    computed: {
        headers() {
            return [];
        }
    }
};
</script>
