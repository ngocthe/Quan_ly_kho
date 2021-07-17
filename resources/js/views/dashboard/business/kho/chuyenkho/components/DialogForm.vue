<template>
    <v-dialog v-model="showDialog" persistent  :height="editing ? 'calc(100vh - 550px)' : null" max-width="65vw">
        <v-card :loading="loading">
            <v-card-title>
                <span class="headline">{{ title }}</span>
            </v-card-title>
             <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" outlined text @click="closeDialog">{{
                    $t("cancel")
                }}</v-btn>
                <v-btn
                    v-if="!editing"
                    color="blue darken-1"
                    text
                    outlined
                    @click="createData"
                    >{{ $t("create") }}</v-btn
                >
                <v-btn v-else color="blue darken-1" outlined text @click="updateData">
                   Cập nhật
                </v-btn>
            </v-card-actions>
            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="6">
                                 <DatePicker
                                    label="Ngày"
                                    :value="form.ngay"
                                    @updatevalue="form.ngay=$event"
                                />
                            </v-col>
                            <v-col cols="12" sm="6">
                                  <v-text-field
                                    v-model="form.so_phieu"
                                    :label="'Số phiếu'"
                                    dense
                                ></v-text-field>
                            </v-col>

                                <v-col cols="12" sm="6">
                                     <v-autocomplete
                                    v-model="form.tu_kho_id"
                                    :items="options.khos"
                                    item-text="ten"
                                    dense
                                    item-value="id"
                                    :label="'Từ kho'"
                                ></v-autocomplete>
                            </v-col>
                                <v-col cols="12" sm="6">
                                     <v-autocomplete
                                    v-model="form.den_kho_id"
                                    :items="options.khod"
                                    item-text="Đến kho"
                                    dense
                                    item-value="id"
                                    :label="'Xe'"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.ghi"
                                    :label="'Ghi chú'"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                         <v-row >
                            <v-col cols="12">
                                <ProductList
                                    :chitiets="form.chitiets"
                                    :editing="editing"
                                    :options="options"
                                />
                            </v-col>
                        </v-row>

                    </v-container>

                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" outlined text @click="closeDialog">{{
                    $t("cancel")
                }}</v-btn>
                <v-btn
                    v-if="!editing"
                    color="blue darken-1"
                    text
                    outlined
                    @click="createData"
                    >{{ $t("create") }}</v-btn
                >
                <v-btn v-else color="blue darken-1" outlined text @click="updateData">
                   Cập nhật
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import { store, update } from "@/api/business/chuyenkho";
import dialogMixin from "@/mixins/crud/dialog";
import DatePicker from "@/components/DatePicker";
import ProductList from "./ProductList";


//validator import
import {} from "validator";
export default {
    props: ["type"],
    mixins: [dialogMixin(store, update)],
    components: { DatePicker,ProductList },
    computed: {
        title() {
            return this.editing ? this.$t("edit") : this.$t("create");
        },
    },
    data() {
        return {
            rules: {},
            years:[],
               months:[],
		tpc:null
        };
    },
     methods:{

    }, mounted(){
    }
};
</script>
