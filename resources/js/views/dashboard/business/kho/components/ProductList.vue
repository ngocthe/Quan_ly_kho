<template>
    <v-data-table
        :headers="headers"
        :items="products"
        disable-pagination
        fixed-header
        disable-sort
        hide-default-footer
        calculate-widths
        :height="editing ? 'calc(100vh - 550px)' : null"
        item-key=""
        disable-filtering
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar class="custom-toolbar" flat>
                <v-toolbar-title>
                    <span style="font-size: 13px; font-weight: bold"
                        >Quantity:
                        {{ count | money }}
                    </span>
                    <span
                        style="
                            font-size: 13px;
                            font-weight: bold;
                            margin-left: 20px;
                        "
                        >{{ type === 1 ? "Amount" : "Revenue" }}:
                        {{ amount | money }}{{ unit }}
                    </span>
                    <span
                        style="
                            font-size: 13px;
                            font-weight: bold;
                            margin-left: 20px;
                        "
                        >{{ type === 1 ? "Amount(VNĐ)" : "Revenue(VNĐ)" }}:
                        {{ amountInVND | money }}
                    </span>

                    <span
                        v-if="type !== 1"
                        style="
                            font-size: 13px;
                            font-weight: bold;
                            margin-left: 20px;
                        "
                        >Cost: {{ cost | money }}
                    </span>
                    <span
                        v-if="type !== 1"
                        style="
                            font-size: 13px;
                            font-weight: bold;
                            margin-left: 20px;
                        "
                        >Profit: {{ profit | money }}
                    </span>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn
                    v-if="editing"
                    @click="addProduct"
                    class="mx-2"
                    small
                    fab
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-plus</v-icon>
                </v-btn>
            </v-toolbar>
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
        <!-- <template v-slot:item.plastic_id="{ item }">
            <v-autocomplete
                :readonly="!editing"
                v-model="item.plastic_id"
                :items="options.plasticTypes"
                item-text="name"
                @change="item.product_id = ''"
                item-value="id"
                dense
            ></v-autocomplete>
        </template> -->
        <template v-slot:item.product_id="{ item }">
            <v-autocomplete
                :readonly="!editing"
                v-model="item.product_id"
                :items="options.products"
                item-text="name"
                item-value="id"
                style="width:100%"
                @change="
                    item.cost_price = options.products.find(
                        item => item.id === $event
                    ).price
                "
                dense
            ></v-autocomplete>
        </template>
        <template v-slot:item.name="{ item }">
            {{
                item.product_id
                    ? options.products.find(
                          product => product.id === item.product_id
                      ).name
                    : ""
            }}
        </template>
        <template v-slot:item.quantity="{ item }">
            <v-text-field
                v-model="item.quantity"
                type="number"
                :readonly="!editing"
                :min="0"
                dense
            ></v-text-field>
        </template>
        <template v-slot:item.price="{ item }">
            <v-text-field
                v-model="item.price"
                type="number"
                :min="0"
                :readonly="!editing"
                dense
            ></v-text-field>
        </template>
        <template v-slot:item.cost_price="{ item }">
            <v-text-field
                v-model="item.cost_price"
                type="number"
                :min="0"
                :readonly="!editing"
                dense
            ></v-text-field>
            <!-- {{ item.cost_price | money }} -->
        </template>
         <template v-slot:item.po_no="{ item }">
            <v-text-field
                v-model="item.po_no"
                :readonly="!editing"
                dense
            ></v-text-field>
            <!-- {{ item.cost_price | money }} -->
        </template>
     
        <template v-slot:item.amount="{ item }">
            {{ (item.price * item.quantity).toFixed(2) | money }}
        </template>
        <template v-slot:item.amount_in_vnd="{ item }">
            {{ (item.price * rate * item.quantity).toFixed(0) | money }}
        </template>
        <template v-slot:item.cost="{ item }">
            {{ (item.cost_price * item.quantity) | money }}
        </template>
        <template v-slot:item.profit="{ item }">
            {{
                (
                    item.price * rate * item.quantity -
                    item.cost_price * item.quantity
                ).toFixed(0) | money
            }}
        </template>
    </v-data-table>
</template>
<script>
export default {
    props: ["products", "options", "editing", "unit", "rate", "type"],
    computed: {
        headers() {
            return [
                // { text: "Resyn type", value: "plastic_id", width: 150 },
                { text: "Grade/Color", value: "product_id", width: 180 },
                {
                    text:
                        this.type === 2
                            ? this.$t("cost_price") + "(VND)"
                            : null,
                    value: this.type === 2 ? "cost_price" : null,
                    width: 130,
                    align: "right"
                },

                {
                    text: this.$t("quantity"),
                    value: "quantity",
                    width: 120
                },
                {
                    text: this.$t("price") + `(${this.unit})`,
                    value: "price",
                    width: 130
                },
                  {
                    text: 'PO',
                    value: "po_no",
                    width: 130
                },
                {
                    text: this.type === 2 ? this.$t("cost") + "(VND)" : null,
                    value: this.type === 2 ? "cost" : null,
                    align: "right"
                },
                { text: this.$t("amount"), value: "amount", align: "right" },
                {
                    text: this.$t("amount") + "(VNĐ)",
                    value: "amount_in_vnd",
                    align: "right"
                },

                {
                    text: this.type === 2 ? this.$t("profit") : null,
                    value: this.type === 2 ? "profit" : null,
                    align: "right"
                },

                {
                    text: this.editing ? this.$t("actions") : null,
                    value: this.editing ? "actions" : null,
                    align: "center"
                }
            ];
        },
        count() {
            return this.products
                .map(item => Number(item.quantity))
                .reduce((sum, i) => (sum += i), 0);
        },
        amountInVND() {
            return this.products
                .map(item =>
                    Number((item.quantity * item.price * this.rate).toFixed(0))
                )
                .reduce((sum, item) => (sum += item), 0);
        },
        amount() {
            return this.products
                .map(item => Number(item.quantity * item.price))
                .reduce((sum, item) => (sum += item), 0)
                .toFixed(2);
        },
        cost() {
            return this.products
                .map(item => Number(item.quantity * item.cost_price))
                .reduce((sum, item) => (sum += item), 0);
        },
        profit() {
            return this.amountInVND - this.cost;
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
        handleDelete(id) {
            this.products.splice(
                this.products.findIndex(p => p.id === id),
                1
            );
        }
    }
};
</script>
