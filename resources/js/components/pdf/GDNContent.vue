<template>
    <section class="pdf-content">
        <v-container style="padding:10px 20px">
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
                    <p>Mẫu số 02 -VT</p>
                    <p>(Ban hành theo QĐ số 48/2006/QĐ-BTC</p>
                    <p>ngày 14/09/2006 của Bộ Trưởng BTC)</p>
                </v-col>
            </v-row>
            <v-row>
                <v-col style="text-align:center" cols="9">
                    <p style="font-weight:bold;font-size:16pt">
                        PHIẾU XUẤT KHO (XUẤT CHO VAY)
                    </p>
                    <p style="font-style:italic;font-size:10pt">
                        LIÊN :2
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
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Họ và tên:
                </v-col>
                <v-col
                    cols="4"
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                >
                    SD CHEMICAL VINA
                </v-col>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Đơn vị:</v-col
                >
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="4"
                    >{{ item.partner.name }}</v-col
                >
            </v-row>
            <v-row>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Địa chỉ:</v-col
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
                    cols="3"
                    >Theo chứng từ, hóa đơn số:</v-col
                >
                <v-col
                    cols="5"
                    style="font-size:10pt;padding-top:5px;padding-bottom:0"
                    >{{ item.document_number }}
                </v-col>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Ngày:</v-col
                >
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >{{ new Date(item.date).toLocaleDateString("en-GB") }}
                </v-col>
            </v-row>

            <v-row class="pdf-item">
                <v-col cols="12">
                    <table id="pdf-table">
                        <tr>
                            <th>STT</th>
                            <th>TÊN HÀNG, QUY CÁCH</th>
                            <th>MH</th>
                            <th>ĐVT</th>
                            <th style="text-align:right">SỐ LƯỢNG</th>
                            <th style="text-align:right">ĐƠN GIÁ</th>
                            <th style="text-align:right">TRỊ GIÁ</th>
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
                                {{
                                    (
                                        product.price *
                                        product.quantity *
                                        item.exchange_rate
                                    ).toFixed(0) | money
                                }}
                            </td>
                        </tr>
                        <tr style="background:orange;font-weight:bold">
                            <td></td>
                            <td>Cộng tiền hàng</td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right">{{ count }}</td>
                            <td></td>
                            <td style="text-align:right">
                                {{ amountInVND | money }}
                            </td>
                        </tr>
                        <tr style="background:orange;font-weight:bold">
                            <td></td>
                            <td>Tiền thuế GTGT</td>
                            <td></td>
                            <td></td>
                            <td style="text-align:left">Tiền thuế</td>
                            <td></td>
                            <td style="text-align:right">_</td>
                        </tr>
                        <tr style="background:orange;font-weight:bold">
                            <td></td>
                            <td>Tổng cộng tiền thành toán</td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right"></td>
                            <td></td>
                            <td style="text-align:right">
                                {{ amount | money }}
                            </td>
                        </tr>
                    </table>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="3">Số tiền bằng chữ:</v-col>
                <v-col cols="9" style="font-weight: bold">{{
                    amount | numberToStr
                }}</v-col>
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
                <v-col cols="3">
                    {{
                        settings.find(item => item.key === "director").value
                    }}</v-col
                >
                <v-col cols="3">
                    {{
                        settings.find(item => item.key === "manager").value
                    }}</v-col
                >
                <v-col cols="3">
                    {{
                        settings.find(item => item.key === "accountant").value
                    }}</v-col
                >
                <v-col cols="3">
                    {{
                        settings.find(item => item.key === "stockkeeper").value
                    }}</v-col
                >
            </v-row>

            <div class="html2pdf__page-break" />
        </v-container>
        <v-container style="padding:10px 20px">
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
                    <p>Mẫu số 02 -VT</p>
                    <p>(Ban hành theo QĐ số 48/2006/QĐ-BTC</p>
                    <p>ngày 14/09/2006 của Bộ Trưởng BTC)</p>
                </v-col>
            </v-row>
            <v-row>
                <v-col style="text-align:center" cols="9">
                    <p style="font-weight:bold;font-size:16pt">
                        PHIẾU XUẤT KHO THEO VỐN
                    </p>
                    <p style="font-style:italic;font-size:10pt">
                        LIÊN :2
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
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Họ và tên:
                </v-col>
                <v-col
                    cols="4"
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                >
                    SD CHEMICAL VINA
                </v-col>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Đơn vị:</v-col
                >
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="4"
                    >{{ item.partner.name }}</v-col
                >
            </v-row>
            <v-row>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Địa chỉ:</v-col
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
                    cols="3"
                    >Theo chứng từ, hóa đơn số:</v-col
                >
                <v-col
                    cols="5"
                    style="font-size:10pt;padding-top:5px;padding-bottom:0"
                    >{{ item.document_number }}
                </v-col>
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >Ngày:</v-col
                >
                <v-col
                    style="font-weight:bold;font-size:10pt;padding-top:5px;padding-bottom:0"
                    cols="2"
                    >{{ new Date(item.date).toLocaleDateString("en-GB") }}
                </v-col>
            </v-row>

            <v-row class="pdf-item">
                <v-col cols="12">
                    <table id="pdf-table">
                        <tr>
                            <th>STT</th>
                            <th>TÊN HÀNG, QUY CÁCH</th>
                            <th>MH</th>
                            <th>ĐVT</th>
                            <th style="text-align:right">SỐ LƯỢNG</th>
                            <th style="text-align:right">ĐƠN GIÁ</th>
                            <th style="text-align:right">TRỊ GIÁ</th>
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
                                {{ product.cost_price | money }}
                            </td>
                            <td style="text-align:right">
                                {{
                                    (
                                        product.cost_price * product.quantity
                                    ).toFixed(0) | money
                                }}
                            </td>
                        </tr>
                        <tr style="background:orange;font-weight:bold">
                            <td></td>
                            <td>Cộng giá vốn</td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right">{{ count }}</td>
                            <td></td>
                            <td style="text-align:right">
                                {{ cost | money }}
                            </td>
                        </tr>
                        <tr style="background:orange;font-weight:bold">
                            <td></td>
                            <td>Tổng cộng tiền thành toán</td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right"></td>
                            <td></td>
                            <td style="text-align:right">
                                {{ cost | money }}
                            </td>
                        </tr>
                    </table>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="3">Số tiền bằng chữ:</v-col>
                <v-col cols="9" style="font-weight: bold">{{
                    cost | numberToStr
                }}</v-col>
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
                <v-col cols="3">
                    {{
                        settings.find(item => item.key === "director").value
                    }}</v-col
                >
                <v-col cols="3">
                    {{
                        settings.find(item => item.key === "manager").value
                    }}</v-col
                >
                <v-col cols="3">
                    {{
                        settings.find(item => item.key === "accountant").value
                    }}</v-col
                >
                <v-col cols="3">
                    {{
                        settings.find(item => item.key === "stockkeeper").value
                    }}</v-col
                >
            </v-row>
        </v-container>
    </section>
</template>
<script>
import convertNumberToString from "@/utils/number";
export default {
    props: ["item", "products", "settings"],
    methods: {
        transformDate(date) {
            const arr = date.split("-");
            return `Ngày ${arr[2]} Tháng ${arr[1]} Năm ${arr[0]}`;
        }
    },
    filters: {
        numberToStr(value) {
            let str = convertNumberToString(value).trim() + " đồng";
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    },
    computed: {
        count() {
            return this.item.products
                .map(item => Number(item.quantity))
                .reduce((sum, i) => (sum += i), 0);
        },

        amount() {
            return this.item.products
                .map(item => Number((item.quantity * item.price).toFixed(2)))
                .reduce((sum, item) => (sum += item), 0);
        },
        amountInVND() {
            return this.item.products
                .map(item =>
                    Number(
                        (
                            item.quantity *
                            item.price *
                            this.item.exchange_rate
                        ).toFixed(0)
                    )
                )
                .reduce((sum, item) => (sum += item), 0);
        },
        cost() {
            return this.item.products
                .map(item => Number(item.quantity * item.cost_price))
                .reduce((sum, item) => (sum += item), 0);
        }
    }
};
</script>
<style lang="scss"></style>
