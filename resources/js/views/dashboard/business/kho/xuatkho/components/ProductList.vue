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

         <template v-slot:item.phe_lieu_id="{ item }">
            <v-autocomplete
                v-model="item.phe_lieu_id"
                :items="options.phelieus"
                item-text="ten"
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
                :min="0"
                dense
            ></v-text-field>
        </template>
        <template v-slot:item.so_luong_de_xuat="{ item }">
            <v-text-field
                v-model="item.so_luong_de_xuat"
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
    props: ["chitiets", "editing","options"],
    computed: {
        headers() {
            return [
                { text: "Phế liệu", value: "phe_lieu_id", width: 200 },
                { text: "Đơn vị", value: "dvt", width: 100 },
                { text: "Đề xuất", value: "so_luong_de_xuat", width: 180 },
                 { text: "Thực tế", value: "so_luong_thuc_te", width: 180 },
                { text: "Đơn giá", value: "don_gia", width: 180 },
                 {
                    text: this.$t("actions") ,
                    value: "actions" ,
                    align: "center"
                }
            ];
        }
        
    },

    methods: {
        addPheLieu() {
            this.chitiets.push({
                id: Math.random(),
                phe_lieu_id:null,
                dvt: 'Kg',
                so_luong_de_xuat: 0,
                so_luong_thuc_te:0,
                don_gia: 0
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
