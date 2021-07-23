<template>
    <v-dialog v-model="showDialog" persistent  :height="editing ? 'calc(100vh - 550px)' : null" max-width="85vw">
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
                    @click="createData2()"
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
                                    @change="getDataPL()"
                                    :value="form.ngay"
                                    @updatevalue="form.ngay=$event"
                                />
                            </v-col>
                            <v-col cols="12" sm="2">
                                <v-select
                                    v-model="form.ca"
                                    :items="[{'id':1,'name':'Sáng'},{'id':2,'name':'Chiều'}]"
                                    item-text="name"
                                    item-value="id"
                                    dense
                                    @change="$emit('handle-search')"
                                    :label="'Ca'"
                                    hide-details
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="2">
                               <v-text-field
                                    v-model="form.so_phieu"
                                    :label="'Số phiếu'"
                                    dense
                                ></v-text-field>
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

                                <v-col cols="12" sm="4">
                                     <v-autocomplete
                                    v-model="form.khach_hang_id"
                                    :items="options.khachhangs"
                                    item-text="ten"
                                    @change="getDataPL()"
                                    dense
                                    item-value="id"
                                    :label="'Khách hàng'"
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
                        </v-row>
                        <v-row >
                             <v-col cols="12">Thông tin nhập</v-col>
                            <v-col cols="2">
                            <label>Phế liệu</label>
                            <v-autocomplete
                            @change="form.dvt=options.phelieus.find(
                          product => product.id === form.phe_lieu_id
                                    ).don_vi"
                                v-model="form.phe_lieu_id"
                                :items="options.phelieus"
                                item-text="ma"
                                item-value="id"
                                style="width:100%"
                                dense
                            ></v-autocomplete>
                            </v-col>
                              <v-col cols="2">
                              <label>ĐVT</label>
                            <v-text-field v-model="form.dvt" 
                            disabled
                                dense>
                            </v-text-field>
                              </v-col>
                          <v-col cols="2">
                              <label>SL thực tế</label>
                            <v-text-field
                                v-model="form.so_luong_thuc_te"
                                type="number"
                                dense
                            ></v-text-field>
                            </v-col>
                         <v-col cols="2">
                              <label>SL biên bản</label>
                            <v-text-field
                                v-model="form.so_luong_chung_tu"
                                type="number"
                                dense
                            ></v-text-field>
                            </v-col>
                                 <v-col cols="2">
                                      <label>SL hàng gửi</label>
                            <v-text-field
                                v-model="form.hang_gui"
                                type="number"
                                dense
                            ></v-text-field>
                            </v-col>
                         <v-col cols="2">
                             <label>SL hàng cộng</label>
                            <v-text-field
                                v-model="form.hang_cong"
                                type="number"
                                dense
                            ></v-text-field>
                            </v-col>
                        </v-row>
                         <v-row >
                            <v-col cols="12">
                                <ProductList
                                    @cong="cong($event)"
                                   @tru="tru($event)"
                                    :chitiets="chitiets"
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
                outlined
                    v-if="!editing"
                    color="blue darken-1"
                    text
                    @click="createData2()"
                    >{{ $t("create") }}</v-btn
                >
                <v-btn v-else outlined color="blue darken-1" text @click="updateData">
                   Cập nhật
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import { store, update } from "@/api/business/nhapkho";
import dialogMixin from "@/mixins/crud/dialog";
import DatePicker from "@/components/DatePicker";
import ProductList from "./ProductList";
import { getsophieu,getPL} from "@/api/business/kho";


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
		tpc:null,
        chitiets:[]
        };
    },
     methods:{
         createData2(){
             console.log(this.chitiets);
         },
    async getDataPL(){
        const { data } = await getPL({ngay:this.form.ngay, khach_hang_id:this.form.khach_hang_id});
                console.log(data)
                this.chitiets = data;
                },
                cong(e){
                        this.form.so_luong_thuc_te= parseFloat(this.form.so_luong_thuc_te)+parseFloat(e)
                },
                 tru(e){
                        this.form.so_luong_thuc_te= parseFloat(this.form.so_luong_thuc_te)-parseFloat(e)
                },

    async getSoPhieu() {
                const { data } = await getsophieu('nhapkho');
                console.log(data)
                this.form.kho_id = data.kho_id;
                   this.form.so_phieu = data.so_phieu;
    }
     },
     mounted(){
        this.getSoPhieu()
    }
};
</script>
