<template>
    <v-dialog v-model="showDialog" persistent  :height="editing ? 'calc(100vh - 550px)' : null" max-width="65vw">
        <v-card :loading="loading">
            <v-card-title>
                <span class="headline">{{ title }}</span>
            </v-card-title>
            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="4">
                                 <DatePicker
                                    label="Ngày"
                                    :value="form.ngay"
                                    @updatevalue="form.ngay=$event"
                                />
                            </v-col>
                            <v-col cols="12" sm="4">
                                  <v-text-field
                                    v-model="form.so_phieu"
                                    :label="'Số phiếu'"
                                    dense
                                ></v-text-field>
                            </v-col>
                                     <v-col cols="12" sm="2">
                                <v-autocomplete
                                    v-model="form.tai_khoan_co_id"
                                    :items="options.tkcos"
                                    item-text="so_tk"
                                    dense
                                    item-value="id"
                                    :label="'Có'"
                                ></v-autocomplete>
                            </v-col>
                         
                           <v-col cols="12" sm="2">
                                <v-autocomplete
                                    v-model="form.tai_khoan_no_id"
                                    :items="options.tknos"
                                    item-text="so_tk"
                                    dense
                                    item-value="id"
                                    :label="'Nợ'"
                                ></v-autocomplete>
                            </v-col>
                           
                                <v-col cols="12" sm="4">
                                     <v-autocomplete
                                    v-model="form.doi_tac_id"
                                    :items="options.doitacs"
                                    item-text="ten"
                                    dense
                                    item-value="id"
                                    :label="'Đối tác'"
                                ></v-autocomplete>
                            </v-col>
                                <v-col cols="12" sm="4">
                                     <v-autocomplete
                                    v-model="form.xe_id"
                                    :items="options.xes"
                                    item-text="bks"
                                    dense
                                    item-value="id"
                                    :label="'Xe'"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-autocomplete
                                    v-model="form.kho_id"
                                    :items="options.khos"
                                    item-text="ten"
                                    dense
                                    item-value="id"
                                    :label="'Kho'"
                                ></v-autocomplete>
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
                <v-btn v-else color="blue darken-1" text @click="updateData">
                   Cập nhật
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import { store, update } from "@/api/business/xuatkho";
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
     
    }
};
</script>
