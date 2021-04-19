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
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.name"
                                    :label="$t('name')"
                                    dense
                                    :rules="[(v) => !!v || $t('required')]"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.code"
                                    :label="$t('code')"
                                    dense
                                    :rules="[(v) => !!v || $t('required')]"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.unit"
                                    :label="$t('unit')"
                                    dense
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.account_number"
                                    :label="$t('account_number')"
                                    dense
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    type="number"
                                    :min="0"
                                    v-model="form.balance"
                                    :label="$t('balance')"
                                    dense
                                ></v-text-field>
                            </v-col>
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
import { store, update } from "@/api/business/xe";
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
