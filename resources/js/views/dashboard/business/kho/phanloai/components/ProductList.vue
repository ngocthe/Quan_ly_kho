<template>
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
               <v-spacer></v-spacer>
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
         <template v-slot:item.phe_lieu_id="{ item ,index}">
            <v-autocomplete
                v-model="item.phe_lieu_id"
                @change="them(index)"
                :items="options.phelieus"
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
        <template v-slot:item.so_luong="{ item }">
            <v-text-field
                v-model="item.so_luong"
                @change="sum(item)"
                dense
            ></v-text-field>
        </template>

            <template v-slot:item.kho_id="{ item }">
             <v-autocomplete
                v-model="item.kho_id"
                :items="options.kho2s"
                item-text="ten"
                item-value="id"
                style="width:100%"
                dense
            ></v-autocomplete>
        </template>
        <template v-slot:item.khach_hang_id="{ item }">
             <v-autocomplete
                v-model="item.khach_hang_id"
                :items="options.khachhangs"
                item-text="ten"
                item-value="id"
                style="width:100%"
                dense
            ></v-autocomplete>
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
</template>
<script>
export default {
    props: ["chitiets", "editing","options","kho_id"],
    computed: {
        headers() {
            
            return this.editing?[
                { text: "Phế liệu", value: "phe_lieu_id",width: 250 },
                { text: "Đơn vị", value: "dvt", width: 100 },
                { text: "Số lượng", value: "so_luong"  },
                 { text: "Kho nhập", value: "kho_id"},
                 {
                    text: this.$t("actions") ,
                    value: "actions" ,
                    align: "center"
                }
            ]:[
                { text: "Phế liệu", value: "phe_lieu_id",width: 250 },
                { text: "Đơn vị", value: "dvt", width: 100 },
                { text: "Số lượng", value: "so_luong"  },
                 { text: "Kho nhập", value: "kho_id"},
                { text: "Khách hàng", value: "khach_hang_id"},
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
         if(!this.editing){
             if(this.kho_id!=5){
             this.chitiets[index].kho_id =  this.chitiets[index].phe_lieu_id
                    ? this.options.phelieus.find( product => product.id === this.chitiets[index].phe_lieu_id).kho_id: this.kho_id
                    }
                    }else{
                        this.chitiets[index].kho_id =5
                    }
          var so = index+1;
            if(this.chitiets.length==so){
                this.addPheLieu()
            }
          },
           sum(item){
            var index=this.chitiets.findIndex(p => p.id === item.id)
            console.log(this.chitiets[index].so_luong)
            if(this.chitiets[index].so_luong){
            var array =this.chitiets[index].so_luong.split('+');
            var t= 0;
            array.forEach(element => {
                t = t + parseFloat(element)
            });
            this.chitiets[index].so_luong = t
            }
        },
        addPheLieu() {
            console.log(this.kho_id)
            this.chitiets.push({
                id: Math.random(),
                phe_lieu_id:null,
                dvt: 'Kg',
                so_luong: null,
                kho_id:this.kho_id,
                 khach_hang_id:null,

            });
        },
        handleDelete(id) {
            this.chitiets.splice(
                this.chitiets.findIndex(p => p.id === id),
                1
            );
        }
    }
};
</script>
