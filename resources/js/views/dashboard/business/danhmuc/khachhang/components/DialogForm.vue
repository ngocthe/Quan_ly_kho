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
                                <DatePicker
                                    :label="$t('date_hd')"
                                    :value.sync="form.date_hd"
                                />
                            </v-col>

                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.number_hd"
                                    :label="$t('number_hd')"
                                    dense
                                ></v-text-field>
                            </v-col>

                              <v-col cols="12" sm="6">
                                <v-text-field
                                type="number"
                                    v-model="form.duration"
                                    :label="$t('duration')"
                                    dense
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDialog">{{
                    $t("cancel")
                }}</v-btn>
                <v-btn
                    v-if="!editing"
                    color="blue darken-1"
                    text
                    @click="createData"
                    >{{ $t("create") }}</v-btn
                >
                <v-btn v-else color="blue darken-1" text @click="updateData">{{
                    $t("edit")
                }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import { store, update } from "@/api/business/debt";
import dialogMixin from "@/mixins/crud/dialog";
import DatePicker from "@/components/DatePicker";

//validator import
import {} from "validator";
export default {
    props: ["type"],
    mixins: [dialogMixin(store, update)],
    components: { DatePicker },
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
