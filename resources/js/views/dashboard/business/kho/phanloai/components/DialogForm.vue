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
                            <v-col cols="12" sm="4">
                                 <DatePicker
                                    label="Ngày"
                                      :value="form.ngay"
                                    @updatevalue="form.ngay=$event"
                                />
                            </v-col>
                            <v-col cols="12" sm="4">
                                 <v-text-field
                                    v-model="form.nguoi_can"
                                    :label="'Người cân'"
                                    dense
                                ></v-text-field>
                            </v-col>
                                     <v-col cols="12" sm="4">
                               <v-text-field
                                    v-model="form.so_phieu"
                                    :label="'Số phiếu'"
                                    dense
                                ></v-text-field>
                            </v-col>

                           <v-col cols="12" sm="6">
                                <v-autocomplete
                                    v-model="form.khach_hang_id"
                                    :items="options.khachhangs"
                                    item-text="ten"
                                    dense
                                    item-value="id"
                                    :label="'Khách hàng'"
                                ></v-autocomplete>
                            </v-col>

                                <v-col cols="12" sm="6">
                                     <v-autocomplete
                                    v-model="form.kho_id"
                                    :items="options.khos"
                                    item-text="ten"
                                    dense
                                    item-value="id"
                                    :label="'Kho'"
                                ></v-autocomplete>
                            </v-col>
                            <!-- <v-col cols="12" sm="4">
                                 <v-text-field
                                    v-model="form.noi_dung"
                                    :label="'Nội dung'"
                                    dense
                                ></v-text-field>
                            </v-col> -->
                                <v-col cols="12" sm="6">
                                     <v-autocomplete
                                    v-model="form.phe_lieu_id"
                                    :items="options.phelieus"
                                    item-text="ma"
                                    dense
                                    item-value="id"
                                    :label="'Phế liệu'"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="4">
                                 <v-text-field
                                    type="number"
                                    v-model="form.so_luong"
                                    :label="'Số lượng xuất'"
                                    dense
                                ></v-text-field>
                            </v-col>



                        </v-row>
                         <v-row >
                            <v-col cols="12">
                                <ProductList
                                    :chitiets="form.chitiets"
                                    :editing="editing"
                                    :options="options"
                                    :kho_id="form.kho_id"
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
import { store, update } from "@/api/business/phanloai";

import { getsophieu} from "@/api/business/kho";

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
     async getSoPhieu() {
                const { data } = await getsophieu('phanloai');
                this.form.kho_id = data.kho_id;
                   this.form.so_phieu = data.so_phieu;

                }
    }, mounted(){
        console.log(1221);
        this.getSoPhieu()
    }
};
</script>
