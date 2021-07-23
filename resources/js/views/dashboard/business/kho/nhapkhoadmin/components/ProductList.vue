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
                <v-toolbar-title>Chọn hàng nhập phân loại thẳng tương ứng</v-toolbar-title>
               <v-spacer></v-spacer>
        
            </v-toolbar>
        </template>

     
     <template v-slot:item.actions="{ item }">
            <v-checkbox
                x-small
                v-model="item.chon"
                @change="item.chon?$emit('cong',item.so_luong_thuc_te):$emit('tru',item.so_luong_thuc_te)"
                class="ml-2"
            >
            </v-checkbox>
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
                { text: "Phế liệu", value: "phe_lieu", width: 200 },
                 { text: "BKS", value: "bks", width: 100 },
                { text: "Đơn vị", value: "dvt", width: 100 },
                { text: "Số lượng thực", value: "so_luong_thuc_te", width: 180 },
                 {
                    text: '' ,
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
        addPheLieu() {
            this.chitiets.push({
                id: Math.random(),
                phe_lieu_id:null,
                dvt: 'Kg',
                so_luong_thuc_te: null,
                so_luong_chung_tu:0,
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
