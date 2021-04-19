<template>
    <v-dialog v-model="showDialog" persistent max-width="600px">
        <v-card :loading="loading">
            <v-card-title>
                <span class="headline">{{ title }}</span>
            </v-card-title>
            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-container>
                        <v-row>
                            <!-- <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.name"
                                    :label="$t('name')"
                                    dense
                                    :rules="[(v) => !!v || 'Item is required']"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-select
                                    v-model="form.role_id"
                                    :items="options.roles"
                                    item-text="name"
                                    item-value="id"
                                    :rules="[(v) => !!v || 'Item is required']"
                                    :label="$t('role')"
                                    single-line
                                ></v-select>
                            </v-col> -->
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDialog"
                    >Huỷ</v-btn
                >
                <v-btn
                    v-if="!editing"
                    color="blue darken-1"
                    text
                    @click="createData"
                    >Thêm</v-btn
                >
                <v-btn v-else color="blue darken-1" text @click="updateData"
                    >Cập nhật</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import { store, update } from "@/api/business/__model__";
import dialogMixin from "@/mixins/crud/dialog";
//validator import
import {} from "validator";
export default {
    mixins: [dialogMixin(store, update)],
    computed: {
        title() {
            return this.editing ? this.$t("edit") : this.$t("create");
        },
    },
    data() {
        return {
            rules: {},
        };
    },
};
</script>
