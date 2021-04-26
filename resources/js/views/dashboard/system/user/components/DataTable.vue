<template>
    <v-data-table
        :headers="getHeaders()"
        :items="tableData"
        disable-pagination
        fixed-header
        disable-sort
        hide-default-footer
        disable-filtering
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar class="custom-toolbar" flat>
                <v-toolbar-title>List</v-toolbar-title>

                <v-spacer></v-spacer>
                <v-btn
                    @click="$emit('handle-create')"
                    class="mx-2"
                    small
                    fab
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-plus</v-icon>
                </v-btn>
            </v-toolbar>
        </template>
        <template>
            <span >QUẢN LÝ KHO</span>
        </template>
        <template v-slot:item.active="{ item }">
            <v-chip v-if="item.active" color="success" dark>{{
                $t("active")
            }}</v-chip>
            <v-chip v-else color="warning" dark>{{ $t("deactive") }}</v-chip>
        </template>
        <template v-slot:item.actions="{ item }">
            <!-- <v-icon small class="mr-2" @click="$emit('handle-edit', item)"
                >mdi-pencil</v-icon
            > -->
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
            <!-- <v-icon small @click="handleDelete(item.id)">mdi-delete</v-icon> -->
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
    </v-data-table>
</template>
<script>
import { destroy } from "@/api/system/user";
import dataTableMixin from "@/mixins/crud/data-table";
export default {
    mixins: [dataTableMixin(destroy)],
    computed: {
        headers() {
            return [
                { text: this.$t("name"), value: "name" },
                { text: "Username", value: "username" },
                { text: "Email", value: "email" },
                { text: this.$t("phone"), value: "phone_number" },
                { text: this.$t("role"), value: "role" },
                { text: this.$t("company"), value: "company" },
                { text: this.$t("status"), value: "active", align: "center" },
                { text: this.$t("actions"), value: "actions", align: "center" }
            ];
        }
    }
};
</script>
