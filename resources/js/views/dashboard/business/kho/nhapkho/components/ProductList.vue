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

         <template v-slot:item.phe_lieu_id="{ item,index }">
            <v-autocomplete
            @change="them(index)"
                v-model="item.phe_lieu_id"
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
        <template v-slot:item.so_luong_thuc_te="{ item }">
            <v-text-field
                v-model="item.so_luong_thuc_te"
                type="number"
                dense
            ></v-text-field>
        </template>
        <template v-slot:item.so_luong_chung_tu="{ item }">
            <v-text-field
                v-model="item.so_luong_chung_tu"
                type="number"
                :min="0"
                dense
            ></v-text-field>
        </template>
            <template v-slot:item.don_gia="{ item }">
            <v-text-field
                v-model="item.don_gia"
                type="number"
                :min="0"
                dense
            ></v-text-field>
        </template>
       <template v-slot:item.chenh_lech="{ item }">
                       {{ (item.so_luong_thuc_te - item.so_luong_chung_tu) | money }}
        </template>
 
     
        <template v-slot:item.actions="{ item }">
            <v-btn
                x-small
                @click="handleDelete(item.id)"
                class="ml-2"
                dark
                color="error"
            >
            Xoá
            </v-btn>
        </template>

        <template>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
          <v-dialog v-model="showFormPL" persistent  :height="editing ? 'calc(100vh - 650px)' : null" max-width="75vw">
                <v-card >
                    <v-card-title>
                        <span class="headline">Phân loại</span>
                    </v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" outlined text @click="showFormPL=false">{{

                            $t("cancel")
                        }}</v-btn>
                        <v-btn
                            color="blue darken-1"
                            text
                            outlined
                            >Ok</v-btn
                        >
                    
                    </v-card-actions>
                    <v-card-text>
                    </v-card-text>
                </v-card>
          </v-dialog>
    </v-data-table>
</template>
<script>
export default {
    props: ["chitiets", "editing","options"],
      data() {
        return {
           showFormPL:false,
           singleExpand:true,
           expanded:[]
        };
    },
    computed: {
        headers() {
             if(!this.$store.state.user.roles[0]==='Thủ Kho')
            return [
                { text: "Phế liệu", value: "phe_lieu_id", width: 200 },
                { text: "Đơn vị", value: "dvt", width: 100 },
                { text: "Số lượng thực", value: "so_luong_thuc_te", width: 180 },
                 { text: "Số lượng biên bản", value: "so_luong_chung_tu", width: 180 },
                { text: "Đơn giá", value: "don_gia", width: 180 },
                { text: "Chênh lệch", value: "chenh_lech", width: 100 },
                 { text: '', value: 'data-table-expand' },
                
            ];
            else
            return [
                { text: "Phế liệu", value: "phe_lieu_id", width: 200 },
                { text: "Đơn vị", value: "dvt", width: 150 },
                { text: "Số lượng thực", value: "so_luong_thuc_te", width: 200 },
                 { text: 'Phân loại', value: 'data-table-expand' , width: 200},
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
                var so = index+1;
            if(this.chitiets.length==so){
                this.addPheLieu()
            }
            console.log(so)
        },
         them2(index2,index){
             console.log(index)
              var index=this.chitiets.findIndex(p => p.id === index)
                var so = index2+1;
            if(this.chitiets[index].phanloais.length==so){
                this.chitiets[index].phanloais.push({
                     id: Math.random(),
                         phe_lieu_id:null,
                        dvt: 'Kg',
                        so_luong: null
                })
            }
        },
        phanLoai(id){
            this.showFormPL=true
        },
        addPheLieu() {
            this.chitiets.push({
                id: Math.random(),
                phe_lieu_id:null,
                dvt: 'Kg',
                so_luong_thuc_te: null,
                so_luong_chung_tu:0,
                don_gia: 0,
                phanloais:[
                    {
                        id: Math.random(),
                         phe_lieu_id:null,
                        dvt: 'Kg',
                        so_luong: null
                    }
                ]
            });
        },

        handleDelete(id) {
            this.chitiets.splice(
                this.chitiets.findIndex(p => p.id === id),
                1
            );
        },
        handleDelete2(id_Cah,id){
            var index=this.chitiets.findIndex(p => p.id === id_Cah)
                            this.chitiets[index].phanloais.splice(
                        this.chitiets[index].phanloais.findIndex(p => p.id === id),
                1
            );
        }
    }
};
</script>
<style>
.ql-align-center{text-align: center;}
.ql-align-justify{text-align: justify;}
.ql-align-center{text-align: center;}
.ql-align-justify{text-align: justify;}
.styled-table {
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
}

.styled-table th,
.styled-table td {
    padding: 12px 15px;
    border: 1px solid #fff;
}

.styled-table tr{
background-color: #e6e6e6;
}
.styled-table tr:nth-child(even) {background-color: #d6d6d6;}
.styled-table th {
    background-color: #032766;
    color: #ffffff;
    text-align: left;
}
</style>
