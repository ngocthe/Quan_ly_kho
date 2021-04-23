<template>
    <v-dialog
        v-if="showDialog"
        v-model="showDialog"
        persistent
        max-width="80vw"
    >
        <v-card :loading="loading">
            <v-card-title>
                <span class="headline">{{ title }}</span>
            </v-card-title>
            <v-card-text class="px-0 py-0">
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-container fluid>
                        <v-row>
                            <v-col cols="12" sm="4">
                                <v-text-field
                                    v-model="form.document_number"
                                    :label="$t('document_no')"
                                    dense
                                    :rules="[v => !!v || $t('required')]"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-autocomplete
                                    v-if="type !== 1"
                                    v-model="form.partner_id"
                                    :items="options.customers"
                                    item-text="name"
                                    dense
                                    item-value="id"
                                    :rules="[v => !!v || $t('required')]"
                                    :label="$t('customer')"
                                     @change="changeVat1()"
                                ></v-autocomplete>
                                <v-autocomplete
                                    v-else
                                    v-model="form.partner_id"
                                    :items="options.suppliers"
                                    item-text="name"
                                    dense
                                    item-value="id"
                                    :rules="[v => !!v || $t('required')]"
                                    :label="$t('supplier')"
                                    @change="changeVat2()"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-text-field
                                    v-model="form.exchange_rate"
                                    :label="$t('exchange_rate')"
                                    :readonly="form.unit === 'VNĐ'"
                                    dense
                                    type="number"
                                    :rules="[v => !!v || $t('required')]"
                                ></v-text-field
                            ></v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" sm="4">
                                <DatePicker :value.sync="form.date" />
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-select
                                    v-model="form.unit"
                                    :items="['$', 'VNĐ']"
                                    dense
                                    @change="
                                        form.exchange_rate =
                                            $event === 'VNĐ' ? 1 : exchangeRate
                                    "
                                    :rules="[v => !!v || $t('required')]"
                                    :label="$t('unit')"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-select
                                    v-model="form.type"
                                    :items="
                                        notesType[type === 1 ? 'grn' : 'gdn']
                                    "
                                    dense
                                    item-text="name"
                                    item-value="id"
                                    :rules="[v => !!v || $t('required')]"
                                    :label="$t('type')"
                                ></v-select>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" sm="4">
                                <v-text-field
                                    v-model="form.tax"
                                    :label="$t('tax') + '(%)'"
                                    dense
                                    type="number"
                                    :min="0"
                                    :rules="[
                                        v => !!v.toString() || $t('required')
                                    ]"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <ProductList
                                    :chitiets="form.chitiets"
                                    :options="options"
                                    :editing="true"
                                    :type="type"
                                />
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDialog"
                    >Huỷ</v-btn
                >
                <v-btn
                    v-if="!editing"
                    color="blue darken-1"
                    text
                    @click="validateProduct"
                    >Thêm</v-btn
                >
                <v-btn
                    v-else
                    color="blue darken-1"
                    text
                    @click="validateProduct"
                    >Cập nhật</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import {
    store as createGRN,
    update as updateGRN
} from "@/api/business/kho";

import dialogMixin from "@/mixins/crud/dialog";
//validator import
import DatePicker from "@/components/DatePicker";
import ProductList from "./ProductList";
import {} from "validator";
export default {
    props: ["type", "notesType"],
    mixins: [dialogMixin(createGRN, updateGRN)],
    components: { DatePicker, ProductList },
    computed: {
        title() {
            return this.editing ? this.$t("edit") : this.$t("create");
        }
    },
    data() {
        return {
            rules: {}
        };
    },
    methods: {
        changeVat1(){
          this.form.tax = this.options.customers.find(e=>e.id == this.form.partner_id).vat
        },
        changeVat2(){
           this.form.tax = this.options.suppliers.find(e=>e.id == this.form.partner_id).vat
        },
        validateProduct(mode) {
            if (this.form.products.length == 0) {
                this.$snackbar("Chưa có sản phẩm nào", "error");
                return false;
            }
            if (
                this.form.products.some(
                    item =>
                        Number.isNaN(Number(item.price)) ||
                        !Number.isInteger(Number(item.quantity)) ||
                        item.price < 0 ||
                        item.quantity <= 0 ||
                        !item.product_id
                )
            ) {
                this.$snackbar(
                    "Số lượng, sản phẩm và giá, không để trống. Số lượng và giá phải lớn hơn 0, số lượng là số nguyên dương",
                    "error"
                );
                return false;
            }
            if (this.type === 2) {
                if (
                    this.form.products.some(
                        item =>
                            !Number.isInteger(Number(item.cost_price)) ||
                            item.cost_price < 0
                    )
                ) {
                    this.$snackbar(
                        "Giá vốn không được để trống và là số nguyên",
                        "error"
                    );
                    return;
                }
            }
            if (
                new Set(this.form.products.map(item => item.product_id)).size <
                this.form.products.length
            ) {
                this.$snackbar("Trùng sản phẩm", "error");
                return false;
            }
            this.editing ? this.updateData() : this.createData();
        },
        async createData() {
            try {
                const store = this.type === 1 ? createGRN : createGDN;
                await this.$refs.form.validate();
                if (!this.valid) return;
                this.loading = true;
                await store(this.form);
                this.reload();
            } catch (error) {
                this.loading = false;
            }
        },
        async updateData() {
            try {
                const update = this.type === 1 ? updateGRN : updateGDN;
                await this.$refs.form.validate();
                if (!this.valid) return;
                this.loading = true;
                await update(this.form.id, this.form);
                this.reload();
            } catch (error) {
                console.log(error);
                this.loading = false;
            }
        },
        async getExchangeRate() {
            const { data } = await getExchangeRate({ exchange_rate: true });
            this.exchangeRate = data.value;
            this.form.exchange_rate = this.type === 1 ? this.exchangeRate : 1;
        }
    },
    created() {
        if (!this.editing) {
            this.form.products = [];
            this.getExchangeRate();
        }
    }
};
</script>
