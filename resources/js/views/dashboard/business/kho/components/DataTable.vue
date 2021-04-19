<template>
    <div>
        <ag-grid-vue
            v-if="options.products"
            style="width: 100%; height: calc(100vh - 254px);"
            class="ag-theme-alpine"
            :columnDefs="columnDefs1"
            :rowData="tableData"
            :animateRows="true"
            :gridOptions="gridOptions"
            :defaultColDef="defaultColDef"
            :pinnedTopRowData="summary"
            @first-data-rendered="onFirstDataRendered"
            @column-resized="onColumnResized"
        ></ag-grid-vue>
        <vue-html2pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="false"
            :preview-modal="true"
            :paginate-elements-by-height="1400"
            filename="hee hee"
            :pdf-quality="2"
            :manual-pagination="true"
            pdf-format="a4"
            pdf-orientation="portrait"
            pdf-content-width="800px"
            ref="html2Pdf"
            v-if="currentItem"
            @hasDownloaded="$loader(false)"
        >
            <GRNContent
                :item="currentItem"
                v-if="type === 1"
                :products="options.products"
                slot="pdf-content"
                :settings="settings"
            />
            <GDNContent
                v-else
                :item="currentItem"
                :products="options.products"
                slot="pdf-content"
                :settings="settings"
            />
        </vue-html2pdf>
    </div>
</template>
<script>
import { destroy as destroyGRN } from "@/api/business/kho";
import { exportExcel as exportGRN } from "@/api/business/kho";
import dataTableMixin from "@/mixins/crud/data-table";
import ProductList from "./ProductList";
import VueHtml2pdf from "vue-html2pdf";
import GRNContent from "@/components/pdf/GRNContent";
import GDNContent from "@/components/pdf/GDNContent";
import { AgGridVue } from "ag-grid-vue";
import Button from "./Button";
import "ag-grid-enterprise";
export default {
    props: ["options", "notes", "type", "summary"],
    mixins: [dataTableMixin(destroyGRN)],
    components: { ProductList, VueHtml2pdf, GRNContent, GDNContent, AgGridVue },
    data() {
        return {
            currentItem: "",
            settings: [],
            columnDefs: null,
            defaultColDef: null,
            gridApi: null,
            columnApi: null,
            gridOptions: null
        };
    },
    watch: {
        type(val) {
            this.gridOptions.api.setColumnDefs([]);
            this.gridOptions.api.setColumnDefs(
                val === 1 ? this.columnDefs1 : this.columnDefs2
            );
        }
    },
    methods: {
        async handleDelete(id) {
            try {
                const destroy = this.type === 1 ? destroyGRN : destroyGDN;
                const res = await this.$confirm(
                    this.$t("delete_confirm_text"),
                    {
                        title: this.$t("delete_confirm_title"),
                        buttonTrueText: this.$t("delete_confirm_true_text"),
                        buttonFalseText: this.$t("delete_confirm_false_text")
                    }
                );
                if (res) {
                    await destroy(id);
                    this.$snackbar(
                        this.$t("delete_success_message"),
                        "success"
                    );
                    this.$emit("handle-delete");
                }
            } catch (error) {
                console.log(error);
            }
        },
        exportData(note_id) {
            this.$loader(true);
            this.currentItem = this.notes.find(item => item.id === note_id);
            this.$nextTick(() => this.$refs.html2Pdf.generatePdf());
        },
        async getSettings() {
            const { data } = await getSettings();
            this.settings = data;
            this.$eventBus.$on("export-note", e => this.exportData(e));
        },
        ////////////////////////////////
        onColumnResized(params) {
            if (this.gridColumnApi) this.gridApi.resetRowHeights();
        },
        onFirstDataRendered(params) {
            if (this.gridApi) this.gridApi.sizeColumnsToFit();
        },
        numberFormatter(value) {
            if (value != null)
                return value
                    .toString()
                    .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            return null;
        }
    },
    created() {
        this.getSettings();
        this.$eventBus.$on("delete-note", e => this.handleDelete(e));
    },
    mounted() {
        this.gridApi = this.gridOptions.api;
        this.gridColumnApi = this.gridOptions.columnApi;
    },
    beforeMount() {
        this.gridOptions = {};
        this.defaultColDef = {};
        this.columnDefs1 = [
            {
                headerName: this.$t("document_no"),
                field: "document_number",
                width: 150,
                 filter: 'agTextColumnFilter',
                pinned: "left",
                cellClassRules: {
                    "row-pinned": "node.rowPinned"
                }
            },
            {
                headerName: this.$t("date"),
                field: "date",
                width: 110,
                pinned: "left"
            },
            {
                headerName: this.$t("supplier"),
                field: "partner.name",
                 filter: 'agTextColumnFilter',
                width: 280
            },

            {
                headerName: this.$t("product_name"),
                field: "name",
                 filter: 'agTextColumnFilter',
                width: 150
            },
            {
                headerName: this.$t("quantity"),
                field: "quantity",
                valueFormatter: params => this.numberFormatter(params.value),
                width: 110,
                type: "rightAligned",
                cellClassRules: {
                    "row-pinned": "node.rowPinned"
                }
            },
            {
                headerName: this.$t("price") + "(USD)",
                field: "price_in_usd",
                width: 150,
                type: "rightAligned"
            },
            {
                headerName: this.$t("price") + "(VNĐ)",
                field: "price_in_vnd",
                valueFormatter: params => this.numberFormatter(params.value),
                width: 150,
                type: "rightAligned"
            },
            {
                headerName: this.$t("amount") + "(USD)",
                field: "amount_in_usd",
                valueFormatter: params => this.numberFormatter(params.value),
                width: 160,
                type: "rightAligned",
                cellClassRules: {
                    "row-pinned": "node.rowPinned"
                }
            },
            {
                headerName: this.$t("amount") + "(VNĐ)",
                field: "amount_in_vnd",
                valueFormatter: params => this.numberFormatter(params.value),
                width: 190,
                type: "rightAligned",
                cellClassRules: {
                    "row-pinned": "node.rowPinned"
                }
            },
            {
                headerName: "VAT(%)",
                field: "tax",
                valueFormatter: params => this.numberFormatter(params.value),
                width: 100,
                type: "rightAligned"
            },
            {
                headerName: this.$t("total_amount"),
                field: "total_amount",
                valueFormatter: params => this.numberFormatter(params.value),
                width: 190,
                type: "rightAligned",
                cellClassRules: {
                    "row-pinned": "node.rowPinned"
                }
            },

            {
                headerName: this.$t("actions"),
                cellRendererFramework: Button,
                width: 160,
                pinned: "right"
            }
        ];
        this.columnDefs2 = [
            ...this.columnDefs1,
            {
                headerName: this.$t("profit"),
                field: "profit",
                valueFormatter: params => this.numberFormatter(params.value),
                width: 190,
                type: "rightAligned",
                cellClassRules: {
                    "row-pinned": "node.rowPinned"
                }
            }
        ];
    }
};
</script>
<style lang="scss">
@import "~ag-grid-community/dist/styles/ag-grid.css";
@import "~ag-grid-community/dist/styles/ag-theme-balham.css";
@import "~ag-grid-community/dist/styles/ag-theme-alpine.css";
@import "~ag-grid-community/dist/styles/ag-theme-material.css";
.grid-cell-centered {
    text-align: center;
}
.grid-cell-left {
    text-align: left;
}
.grid-cell-right {
    text-align: right;
}

.cell-wrap-text {
    white-space: normal !important;
}
.row-pinned {
    color: #e74c3c;
    font-weight: bold;
}
</style>
