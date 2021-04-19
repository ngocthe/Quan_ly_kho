<template>
    <section class="pdf-content">
        <v-container v-for="n in 2" :key="n" style="padding:10px 20px">
            <v-row>
                <v-col
                    style="font-weight: bold;font-style: italic;font-size: 10pt"
                    cols="8"
                >
                    <p>
                        {{
                            settings.find(item => item.key === "company").value
                        }}
                    </p>
                    <p>
                        {{
                            settings.find(item => item.key === "address").value
                        }}
                    </p>
                    <p>
                        {{
                            settings.find(item => item.key === "tax_code").value
                        }}
                    </p>
                </v-col>
                <v-col style="text-align: center;font-size:9pt" cols="4">
                    <p>Mẫu số 01 -VT</p>
                    <p>(Ban hành theo QĐ số 48/2006/QĐ-BTC</p>
                    <p>ngày 14/09/2006 của Bộ Trưởng BTC)</p>
                </v-col>
            </v-row>
            <v-row>
                <v-col style="text-align:center" cols="9">
                    <p style="font-weight:bold;font-size:16pt">
                        PHIẾU NHẬP KHO
                    </p>
                    <p style="font-style:italic;font-size:10pt">
                        LIÊN :{{ n }} - {{ n == 1 ? "Lưu kho" : "Lưu Kế toán" }}
                    </p>
                    <p style="font-style:italic;font-size:10pt">
                        {{ transformDate(item.date) }}
                    </p>
                </v-col>
                <v-col cols="3">
                    <p>
                        Số:
                        <span
                            style="font-weight:bold;display:inline-block;width:calc(100% - 31.69px);text-align:center"
                            >PN{{ item.id }}</span
                        >
                    </p>
                    <p style="font-weight:bold">156</p>
                    <p style="font-weight:bold;text-align:right">
                        <span v-if="item.unit === '$'">$</span
                        >{{ amount | money }}
                    </p>
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Supplier:</v-col
                >
                <v-col
                    cols="6"
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    >{{ item.partner.name }}
                </v-col>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Tax code:</v-col
                >
                <v-col
                    style="font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >{{ item.partner.tax_code }}</v-col
                >
            </v-row>
            <v-row>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Address:</v-col
                >
                <v-col
                    cols="6"
                    style="font-size:10pt;padding-top:5px;padding-bottom:0"
                    >{{ item.partner.address }}
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Invoice No.:</v-col
                >
                <v-col
                    cols="6"
                    style="font-size:10pt;padding-top:5px;padding-bottom:0"
                    >{{ item.document_number }}
                </v-col>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Date:</v-col
                >
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >{{ new Date(item.date).toLocaleDateString("en-GB") }}
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Nhập tại kho:</v-col
                >
                <v-col
                    cols="6"
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                >
                </v-col>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Other:</v-col
                >
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                ></v-col>
            </v-row>
            <v-row class="pdf-item">
                <v-col cols="12">
                    <table id="pdf-table">
                        <tr>
                            <th>STT</th>
                            <th>Model name</th>
                            <th>MH</th>
                            <th>Unit</th>
                            <th style="text-align:right">Q'ty</th>
                            <th style="text-align:right">Unit Price</th>
                            <th style="text-align:right">Amount</th>
                        </tr>
                        <tr v-for="(product, key) in item.products">
                            <td>{{ key + 1 }}</td>
                            <td>
                                {{
                                    products.find(
                                        item => item.id === product.product_id
                                    ).name
                                }}
                            </td>
                            <td>
                                {{
                                    products.find(
                                        item => item.id === product.product_id
                                    ).symbol
                                }}
                            </td>
                            <td>KG</td>
                            <td style="text-align:right">
                                {{ product.quantity }}
                            </td>
                            <td style="text-align:right">
                                <span v-if="item.unit === '$'">$</span
                                >{{ product.price | money }}
                            </td>
                            <td style="text-align:right">
                                <span v-if="item.unit === '$'">$</span
                                >{{
                                    (product.price * product.quantity).toFixed(
                                        item.unit === "$" ? 2 : 0
                                    ) | money
                                }}
                            </td>
                        </tr>
                        <tr style="background:yellow">
                            <td></td>
                            <td>TOTAL</td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right">{{ count }}</td>
                            <td></td>
                            <td style="text-align:right">
                                {{ item.unit === "$" ? "$" : ""
                                }}{{ amount | money }}
                            </td>
                        </tr>
                    </table>
                </v-col>
            </v-row>
            <v-row style="text-align: center;font-weight:bold;font-size:10pt">
                <v-col cols="3">Giám đốc</v-col>
                <v-col cols="3">Quản lý</v-col>
                <v-col cols="3">Kế toán</v-col>
                <v-col cols="3">Thủ kho</v-col>
            </v-row>
            <v-row
                style="text-align: center;font-weight:bold;font-size:10pt;margin-top:30px"
            >
                <v-col cols="3">{{
                    settings.find(item => item.key === "director").value
                }}</v-col>
                <v-col cols="3">{{
                    settings.find(item => item.key === "manager").value
                }}</v-col>
                <v-col cols="3">{{
                    settings.find(item => item.key === "accountant").value
                }}</v-col>
                <v-col cols="3">{{
                    settings.find(item => item.key === "stockkeeper").value
                }}</v-col>
            </v-row>
            <div class="html2pdf__page-break" />
        </v-container>
    </section>
</template>
<script>
export default {
    props: ["item", "products", "settings"],
    methods: {
        transformDate(date) {
            const arr = date.split("-");
            return `Ngày ${arr[2]} Tháng ${arr[1]} Năm ${arr[0]}`;
        }
    },
    computed: {
        count() {
            return this.item.products
                .map(item => Number(item.quantity))
                .reduce((sum, i) => (sum += i), 0);
        },
        amountInVND() {
            return this.item.products
                .map(item =>
                    Number(
                        (item.quantity * item.price * this.item.rate).toFixed(0)
                    )
                )
                .reduce((sum, item) => (sum += item), 0);
        },
        amount() {
            return this.item.products
                .map(item => Number(item.quantity * item.price))
                .reduce((sum, item) => (sum += item), 0)
                .toFixed(this.item.unit === "$" ? 2 : 0);
        },
        cost() {
            return this.item.products
                .map(item => Number(item.quantity * item.cost_price))
                .reduce((sum, item) => (sum += item), 0);
        },
        profit() {
            return this.amountInVND - this.cost;
        }
    }
};
</script>
<style lang="scss"></style>
