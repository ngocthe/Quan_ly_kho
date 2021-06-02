<template>
    <v-row   style="padding:10px">
   <v-col class="pb-0" cols="12">
    <v-row>
        <v-col md="8" cols="12" class="pb-0">
            <v-row>
                 <v-col cols="12" md="6" lg="4" class="pb-0">
                    <DateRangePicker :value.sync="params.ngay"
                /></v-col>
                <!-- <v-col cols="6" md="6" lg="4" class="pb-0">
                    <v-text-field
                        :label="$t('form_search_label')"
                        :placeholder="$t('form_search_placeholder')"
                        clearable
                          @keyup.enter="$emit('handle-search')"
                        outlined
                        dense
                        v-model="params.search"
                    ></v-text-field>
                </v-col> -->
               
                <v-col cols="6" md="6" lg="4" class="pb-0">
                    <v-autocomplete
                                    v-model="params.phe_lieu_id"
                                      @change="getData()"
                                    :items="options.phelieus"
                                    item-text="ma"
                                    clearable
                                    dense
                                    item-value="id"
                                    :label="'Phế liệu'"
                                ></v-autocomplete>
                </v-col>
                
            </v-row>
        </v-col>
        <v-col cols="12" style="padding-top: 20px" md="4">
            <v-btn
                class="float-md-right mr-md-0"
                @click="getData()"
                color="primary"
                medium
                
                >{{ $t("form_search_label") }}</v-btn
            >
            
        </v-col>
    </v-row>
    </v-col>
   <v-col class="pb-0" cols="12">
    <v-data-table
  
        :headers="headers"
        :items="chitiets"
        disable-pagination
        fixed-header
        disable-sort
        calculate-widths
        hide-default-footer
        disable-filtering
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar class="custom-toolbar" flat>
                <v-toolbar-title>Danh sách phế liệu</v-toolbar-title>
                <v-btn
                    @click="addPheLieu"
                    class="mx-2"
                    small
                    fab
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-plus</v-icon>
                </v-btn>
                <!-- <v-btn
                    @click="$emit('handle-export')"
                    class="mx-2"
                    small
                    fab
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-download</v-icon>
                </v-btn> -->
            </v-toolbar>
        </template>
                <template v-slot:item.phe_lieu_id="{ item,index }">
            <v-autocomplete
                v-model="item.phe_lieu_id"
                :items="options.phelieus"
                @change="them(index)"
                item-text="ma"
                item-value="id"
                style="width:100%"
                dense
            ></v-autocomplete>
        </template>
        <template v-slot:item.dvt="{ item }">
            {{
                item.phe_lieu_id
                    ? options.phelieus.find(
                          product => product.id === item.phe_lieu_id
                      ).don_vi
                    : ""
            }}
        </template>
        <template v-slot:item.khoi_luong="{ item }">
            <v-text-field
                v-model="item.khoi_luong"
                type="number"
                :min="0"
                dense
            ></v-text-field>
        </template>
      
       <template v-slot:item.actions="{ item }">
            <v-btn
                x-small
                @click="handleDelete(item.id)"
                class="ml-2"
                fab
                dark
                color="error"
            >
                <v-icon dark>mdi-delete</v-icon>
            </v-btn>
        </template>
      
        <template>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
    </v-data-table>
        </v-col>
    </v-row>
</template>
<script>
import DateRangePicker from "@/components/DateRangePicker";

import { index,tonkho} from "@/api/business/kho";
import indexMixin from "@/mixins/crud/index";
import { index as getPhelieus } from "@/api/business/phelieu";
export default {
  mixins: [
        indexMixin(index, {
              phelieus: {
                func: getPhelieus,
                params: {
                   all: true,
                },
            },
        }),
    ],
    props: ["id"],
        components: { DateRangePicker },
      data() {
        return {
            defaultParams: {
                phe_lieu_id:null,
                ngay: [
                    new Date().toLocaleDateString("en-CA"),
                    new Date().toLocaleDateString("en-CA")
                ],
            },
             chitiets:[]
        };
    },
    computed: {
        headers() {
            return [
                { text: "Mã", value: "phe_lieu_id", width: 180 },
                { text: "Đơn vị", value: "dvt", width: 180 },
                 { text: "Tồn kho đầu kì", value: "dau_ki", width: 180 },
                   { text: "Tổng nhập", value: "nhap", width: 180 },
                  { text: "Tổng xuất", value: "xuat", width: 180 },
                { text: "Tồn kho hiện tại", value: "khoi_luong", width: 180 },
                 {
                    text: this.$t("actions") ,
                    value: "actions" ,
                    align: "center"
                }
            ];
        }
        
    },

    methods: {
        them(index){
            console.log(index)
        },
        addPheLieu() {
            this.chitiets.push({
                id: Math.random(),
                phe_lieu_id: "",
                dvt: 1,
                khoi_luong: 0,
            });
        },
         handleDelete(id) {
            this.chitiets.splice(
                this.chitiets.findIndex(p => p.id === id),
                1
            );
        },
            async getData() {
            try {
                this.$loader(true);
                const { data } = await tonkho(this.$store.state.tonkhoId,this.params);
                this.chitiets = data;
                console.log(data)
            } catch (error) {
                console.log(error);
            } finally {
                this.$loader(false);
            }
         }
      
    },
    mounted(){
        console.log(this.$store.state.tonkhoId)
      this.getData()
    }
};
</script>
