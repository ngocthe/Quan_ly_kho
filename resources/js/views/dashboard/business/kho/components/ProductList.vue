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
      
        <template>
            <v-btn color="primary" @click="$emit('handle-reset')"
                >Refresh</v-btn
            >
        </template>
    </v-data-table>
</template>
<script>
export default {
    props: ["chitiets", "editing"],
    computed: {
        headers() {
            return [
                { text: "Mã", value: "phe_lieu.ma", width: 180 },
                { text: "Tên", value: "phe_lieu.ten", width: 180 },
                { text: "Đơn vị", value: "dvt", width: 180 },
                { text: "Số lượng", value: "khoi_luong", width: 180 },
            ];
        }
        
    },

    methods: {
        addProduct() {
            this.products.push({
                id: Math.random(),
                product_id: "",
                quantity: 1,
                price: 0,
                po_no:"",
                cost_price: 0
            });
        },
      
    }
};
</script>
