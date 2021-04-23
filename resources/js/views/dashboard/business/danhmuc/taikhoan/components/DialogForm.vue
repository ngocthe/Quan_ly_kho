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
                                    v-model="form.ma"
                                    :label="'Mã'"
                                    dense
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.so_tk"
                                    :label="'Số TK'"
                                    dense
                                ></v-text-field>
                            </v-col>
                                <v-col cols="12" sm="6">
                                    <v-select
                                    v-model="form.loai"
                                    :items="[{'id':1,'name':'Có'},{'id':0,'name':'Nợ'}]"
                                    item-text="name"
                                    item-value="id"
                                    dense
                                    @change="$emit('handle-search')"
                                    :label="$t('type')"
                                    hide-details
                                ></v-select>
                                     <!-- <v-autocomplete
                                    v-model="form.loai"
                                    :items="[{'id':1,'name':'Có'},{'id':1,'name':'Nợ'}]"
                                    item-text="name"
                                    dense
                                    item-value="id"
                                    :label="'Loại TK'"
                                ></v-autocomplete> -->
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
import { store, update,getyear,getmonth,getamount } from "@/api/business/taikhoan";
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
     
    }
};
</script>
