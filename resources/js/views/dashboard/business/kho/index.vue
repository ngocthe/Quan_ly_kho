<template>
    <v-container
        style="height: calc(100vh - 60px); overflow-y: auto"
        fluid
        class="py-0"
    >
        <v-row>
            <v-col class="py-0" cols="12">
                <Search
                    :params="params"
                    @handle-search="getData(1)"
                    @handle-reset="reset"
                    @handle-create="showDialogForm('create')"
                    @handle-export="exportData()"
                    :options="options"
                    :type="type"
                    @handle-change-type="type = $event"
                    :notes-type="notesType"
                />
            </v-col>
            <v-col class="py-0" cols="12">
                <DataTable
                    :form="form"
                    :table-data="tableData"
                    :summary="summary"
                    @handle-edit="showDialogForm('edit', $event)"
                    @handle-delete="getData()"
                    :options="options"
                    :notes="notes"
                    :type="type"
                />
            </v-col>
        </v-row>
        <DialogForm
            v-if="showDialog"
            @created="getData(1)"
            @updated="getData"
            :options="options"
            :show-dialog.sync="showDialog"
            :editing="editing"
            :form="form"
            :notes-type="notesType"
            :type="type"
        />
    </v-container>
</template>

<script>
import DataTable from "./components/DataTable";
import Search from "./components/Search";
import DialogForm from "./components/DialogForm";
import Pagination from "@/components/Pagination";
import { index} from "@/api/business/kho";

import indexMixin from "@/mixins/crud/index";
export default {
       mixins: [indexMixin(index)],
    components: { DataTable, Search, DialogForm, Pagination },
    data() {
        return {
            type: 1,
            notes: [],
            defaultParams: {
                search: "",
                page: 1,
                per_page: 15,
                partner_id: "",
                date: [
                    new Date(
                        new Date().getFullYear(),
                        new Date().getMonth(),
                        1
                    ).toLocaleDateString("en-CA"),
                    new Date().toLocaleDateString("en-CA")
                ],
                type: 1
            },
            form: {
                id: undefined,
                date: new Date().toLocaleDateString("en-CA"),
                partner_id: "",
                unit: "$",
                tax: 0,
                document_number: "",
                exchange_rate: 0,
                products: [],
                type: 1
            },
            notesType: {
                gdn: [
                    { id: 1, name: "Xuất bán hàng" },
                    { id: 2, name: "Xuất cho vay" },
                    { id: 3, name: "Xuất hàng mẫu" }
                ],
                grn: [
                    { id: 1, name: "Nhập kho" },
                    { id: 2, name: "Nhập vay" }
                ]
            },
            tableData: [],
            summary: []
        };
    },
    methods: {
        async getData(page = null) {
            try {
                this.$loader(true);
                if (page) this.params.page = page;
                const index = this.type !== 1 ? getGDN : getGRN;
                const { data } = await index(this.params);
                this.notes = data;
                this.tableData = data;
                this.transformData();
                this.$router
                    .push({ path: this.$route.path, query: { ...this.params } })
                    .catch(err => {});
            } catch (error) {
                console.log(error);
            } finally {
                this.$loader(false);
            }
        },
        transformData() {
            for (const noteIndex in this.tableData) {
                let totalAmount = 0;
                let totalCost = 0;
                let totalAmountInVND = 0;
                let totalProfit = 0;
                for (const product of this.tableData[noteIndex].products) {
                    for (const field in this.tableData[noteIndex]) {
                        if (field != "products" && field != "id") {
                            product[field] = this.tableData[noteIndex][field];
                        }
                    }
                    product.note_id = this.tableData[noteIndex].id;

                    product.price_in_usd =
                        this.tableData[noteIndex].unit === "$"
                            ? (+product.price).toFixed(3)
                            : null;
                    product.price_in_vnd = +(
                        product.price * this.tableData[noteIndex].exchange_rate
                    ).toFixed(0);
                    product.amount_in_usd =
                        this.tableData[noteIndex].unit === "$"
                            ? +(product.price * product.quantity).toFixed(2)
                            : null;
                    product.amount_in_vnd = +(
                        product.price_in_vnd * product.quantity
                    ).toFixed(0);
                    // totalAmount += +product.amount;
                    // totalAmountInVND += +product.amount_in_vnd;
                    product.total_amount = +(
                        product.amount_in_vnd *
                        (1 + this.tableData[noteIndex].tax / 100)
                    ).toFixed(0);
                    if (this.type === 1) {
                        product.cost_price = 0;
                        product.profit = 0;
                        product.cost = 0;
                    } else {
                        product.cost = product.cost_price * product.quantity;
                        product.profit = product.amount_in_vnd - product.cost;
                    }
                }
                // for (const product of this.tableData[noteIndex].products) {
                //     product.total_amount = totalAmount;
                //     product.total_amount_in_vnd = totalAmountInVND;
                //     if (this.type === 1) {
                //         product.total_cost = null;
                //         product.total_profit = null;
                //     } else {
                //         product.total_cost = totalCost;
                //         product.total_profit = totalProfit;
                //     }
                // }
            }
            this.tableData = this.tableData.map(item => item.products).flat();
            this.summary = [
                {
                    document_number: "SUM",
                    quantity: this.tableData.reduce(
                        (total, item) => total + item.quantity,
                        0
                    ),
                    amount_in_vnd: this.tableData
                        .reduce((total, item) => total + item.amount_in_vnd, 0)
                        .toFixed(0),
                    amount_in_usd: this.tableData
                        .reduce((total, item) => total + +item.amount_in_usd, 0)
                        .toFixed(2),
                    total_amount: this.tableData
                        .reduce((total, item) => total + item.total_amount, 0)
                        .toFixed(0),
                    profit: this.tableData
                        .reduce((total, item) => total + item.profit, 0)
                        .toFixed(0)
                }
            ];
        },
        showDialogForm(mode, id = null) {
            const data = this.notes.find(item => item.id === id);
            if (mode == "edit") {
                this.editing = true;
                for (let field in this.form) {
                    this.form[field] = data[field];
                }
            } else {
                this.form = JSON.parse(JSON.stringify(this.defaultForm));
                this.form.id = undefined;
                this.form.unit = this.type === 1 ? "$" : "VNĐ";
                this.editing = false;
            }
            this.showDialog = true;
        },
        async exportData() {
            this.$loader(true);
            const index = this.type !== 1 ? getGDN : getGRN;
            const data = await index({ ...this.params, export: true }, "blob");
            const url = URL.createObjectURL(
                new Blob([data], {
                    type:
                        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                })
            );
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", `${this.model || "export"}.xlsx`);
            document.body.appendChild(link);
            link.click();
            this.$loader(false);
        }
    },
    watch: {
        type(val) {
            this.tableData = [];
            this.reset();
        }
    },
    created() {
        this.$eventBus.$on("edit-note", e => this.showDialogForm("edit", e));
    }
};
</script>
<style lang="scss">
.row-pinned {
    color: #e74c3c;
    font-weight: bold;
}
</style>
