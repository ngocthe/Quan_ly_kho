<template>
    <div v-if="!params.node.rowPinned">
        <v-btn x-small @click="handleEdit" fab dark color="primary">
            <v-icon dark>mdi-pencil</v-icon>
        </v-btn>
        <v-btn
            x-small
            @click="handleDelete"
            class="ml-2"
            fab
            dark
            color="error"
        >
            <v-icon dark>mdi-delete</v-icon>
        </v-btn>
        <v-btn x-small @click="exportData" class="ml-2" fab dark color="blue">
            <v-icon dark>mdi-download</v-icon>
        </v-btn>
    </div>
</template>
<script>
import Vue from "vue";
export default Vue.extend({
    methods: {
        handleEdit(note_id) {
            this.$eventBus.$emit("edit-note", this.params.node.data.note_id);
        },
        handleDelete(note_id) {
            this.$eventBus.$emit("delete-note", this.params.node.data.note_id);
        },
        exportData(note_id) {
            this.$eventBus.$emit("export-note", this.params.node.data.note_id);
        }
    }
});
</script>
