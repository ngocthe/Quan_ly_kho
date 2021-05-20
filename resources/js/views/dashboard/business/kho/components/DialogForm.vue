<template>
    <v-dialog v-model="showDialog" persistent  :height="editing ? 'calc(100vh - 550px)' : null" :max-width="editing ? '70vw':'600px'">
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
                                    v-model="form.ma"
                                    :label="'Mã'"
                                    dense
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.ten"
                                    :label="'Tên'"
                                    dense
                                ></v-text-field>
                            </v-col>
                                <v-col cols="12" sm="6">
                                     <v-autocomplete
                                    v-model="form.thu_kho_id"
                                    :items="options.thukhos"
                                    item-text="ten"
                                    dense
                                    item-value="id"
                                    :label="'Thủ kho'"
                                ></v-autocomplete>
                            </v-col>
                                <v-col cols="12" sm="6">
                                     <v-autocomplete
                                    v-model="form.nhan_vien_ban_hang_id"
                                    :items="options.nvbhs"
                                    item-text="ten"
                                    dense
                                    item-value="id"
                                    :label="'NVBH'"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.dia_chi"
                                    :label="'Địa chỉ'"
                                    dense
                                ></v-text-field>
                            </v-col>
                         
                        </v-row>
                         <v-row v-if="editing">
                            <v-col cols="12">
                                <ProductList
                                    :chitiets="form.chitiets"
                                    :editing="true"
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
                <v-btn v-else color="blue darken-1" text @click="updateData">{{
                    $t("edit")
                }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import { store, update } from "@/api/business/kho";
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
