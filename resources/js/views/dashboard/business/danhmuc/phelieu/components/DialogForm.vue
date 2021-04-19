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
                                <DatePicker :value.sync="form.date" />
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-select
                                    v-model="form.ck"
                                    :items="[
                                        { id: 1, name: $t('ck') },
                                        { id: 0, name: $t('tm') },
                                    ]"
                                    item-text="name"
                                    item-value="id"
                                    dense
                                    :label="$t('type_ck')"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="6" v-if="form.ck == 1">
                                <v-autocomplete
                                    v-model="form.bank_id"
                                    :items="options.banks"
                                    item-text="account_number"
                                    item-value="id"
                                    dense
                                    :rules="[(v) => !!v || $t('required')]"
                                    :label="$t('account_number')"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.description"
                                    :label="$t('description_transaction')"
                                    dense
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-select
                                    v-model="form.type"
                                    :items="[
                                        { id: 1, name: $t('type_in') },
                                        { id: 2, name: $t('type_out') },
                                    ]"
                                    item-text="name"
                                    item-value="id"
                                    dense
                                    :rules="[(v) => !!v || $t('required')]"
                                    :label="$t('type_transaction')"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="6" v-if="form.type == 1">
                                <v-select
                                    v-model="form.type_t1"
                                    :items="[
                                        { id: 1, name: $t('type_t11') },
                                        { id: 2, name: $t('type_t12') },
                                        { id: 3, name: $t('type_t13') },
                                        { id: 4, name: $t('type_t14')}
                                    ]"
                                    @change="function(){if(form.type_t1==4)form.ck=0}"
                                    item-text="name"
                                    item-value="id"
                                    dense
                                    :rules="[(v) => !!v || $t('required')]"
                                    :label="$t('type_title_t')"
                                ></v-select>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                v-if="form.type_t1 == 1 && form.type == 1"
                            >
                                <v-autocomplete
                                    v-model="form.type_t2"
                                    :items="options.customers"
                                    item-text="name"
                                    @change="getYear()"
                                    item-value="id"
                                    dense
                                    :rules="[(v) => !!v || $t('required')]"
                                    :label="$t('customer')"
                                ></v-autocomplete>
                            </v-col>

                            <v-col cols="12" sm="6" v-if="form.type == 2">
                                <v-select
                                    v-model="form.type_c1"
                                    :items="[
                                        { id: 1, name: $t('type_c11') },
                                        { id: 2, name: $t('type_c12') },
                                        { id: 3, name: $t('type_c13') },
                                        { id: 4, name: $t('type_c14') },
                                         { id: 5, name: $t('type_c15') },
                                            { id: 6, name: $t('type_c16') },
                                    ]"
                                     @change="function(){if(form.type_c1==6)form.ck=0}"
                                    item-text="name"
                                    item-value="id"
                                    dense
                                    :rules="[(v) => !!v || $t('required')]"
                                    :label="$t('type_title_c')"
                                ></v-select>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                v-if="form.type_c1 == 3 && form.type == 2"
                            >
                                <v-autocomplete
                                    v-model="form.type_c2"
                                    :items="options.suppliers"
                                    item-text="name"
                                    @change="getYear2()"
                                    item-value="id"
                                    dense
                                    :rules="[(v) => !!v || $t('required')]"
                                    :label="$t('supplier')"
                                ></v-autocomplete>
                            </v-col>
                             <v-col cols="12" sm="6"    v-if="form.type_c1 == 5 && form.type == 2">
                                <v-autocomplete
                                    v-model="form.type_c2"
                                    :items="options.banks"
                                    item-text="account_number"
                                    item-value="id"
                                    dense
                                    :rules="[(v) => !!v || $t('required')]"
                                    :label="$t('account_number')"
                                ></v-autocomplete>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                v-if="form.type_c1 == 2 && form.type == 2"
                            >
                                <v-select
                                    v-model="form.type_c2"
                                    :items="[
                                        { id: 1, name: $t('type_c121') },
                                        { id: 2, name: $t('type_c122') },
                                        { id: 3, name: $t('type_c123') },
                                        { id: 4, name: $t('type_c124') },
                                        { id: 5, name: $t('type_c125') },
                                        { id: 6, name: $t('type_c126') },
                                        { id: 7, name: $t('type_c127') },
                                        { id: 8, name: $t('type_c128') },
                                        { id: 9, name: $t('type_c129') },
                                        { id: 10, name: $t('type_c120') },
                                    ]"
                                    item-text="name"
                                    item-value="id"
                                    dense
                                    :rules="[(v) => !!v || $t('required')]"
                                    :label="$t('type_fix')"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-select
                                    v-model="form.unit_vnd"
                                    :items="[
                                        { id: 1, name: 'VND' },
                                        { id: 0, name: 'USD' },
                                    ]"
                                    item-text="name"
                                    item-value="id"
                                    dense
                                    :label="$t('type_money')"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.amount"
                                    type="number"
                                    :min="0"
                                    :label="$t('amount')"
                                    dense
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" v-if="form.ck">
                                <v-text-field
                                    v-model="form.fee_ck"
                                    type="number"
                                    :min="0"
                                    :label="$t('fee_ck')"
                                    dense
                                ></v-text-field>
                            </v-col>
                            <v-col  cols="12" sm="6">
                            <v-autocomplete
                                v-if="(form.type_c1 == 3 && form.type == 2)||(form.type_t1 == 1 && form.type == 1)"
                                v-model="form.year"
                                :items="years"
                                item-text="year"
                                item-value="year"
                                :label="$t('year')"
                                @change="getMonth()"
                                clearable
                                outlined
                                dense
                            ></v-autocomplete>
                    </v-col>
                                <v-col  cols="12" sm="6">
                            <v-autocomplete 
                            v-if="(form.type_c1 == 3 && form.type == 2)||(form.type_t1 == 1 && form.type == 1)"
                                    v-model="form.month"
                                    :items="months"
                                    item-text="month"
                                    item-value="month"
                                    :label="$t('month')"
                                    clearable
                                    @change="getAmount"
                                    outlined
                                    dense
                                ></v-autocomplete>
                        </v-col>
                        
                        </v-row>

                        <v-row> <b style="font-weight:bold; margin-left:10px">Total: <span style="color:#f81217">{{(parseInt(form.amount)+parseInt(form.fee_ck) )| money}}</span></b>  </v-row>
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
import { store, update,getyear,getmonth,getamount } from "@/api/business/re-ex";
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
            years:[],
               months:[],
		tpc:null
        };
    },
    methods:{
      async  getYear(){
		this.tpc =this.form.type_t2
            var data =await getyear(this.form.type_t2);
            this.years=data.data
            this.getMonth()
        },

 	async  getYear2(){
	    this.tpc =this.form.type_c2
            var data =await getyear(this.form.type_c2);
            this.years=data.data		
            this.getMonth()
        },

     async  getMonth(){
            var data =await getmonth(this.tpc,this.form.year);
            this.months=data.data
             this.getAmount()
        },
        async  getAmount(){
            var data =await getamount(this.tpc,this.form.year,this.form.month);
            this.form.amount=data.data
        }
    }
};
</script>
