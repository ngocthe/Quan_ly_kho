<template>
    <v-row>
        <v-col md="8" cols="12" class="pb-0">
            <v-row>
                <v-col cols="6" md="6" lg="4" class="pb-0">
                    <v-text-field
                        :label="$t('form_search_label')"
                        :placeholder="$t('form_search_placeholder')"
                        clearable
                          @keyup.enter="$emit('handle-search')"
                        outlined
                        dense
                        v-model="params.search"
                    ></v-text-field>
                </v-col>
                <v-col cols="6" md="6" lg="4" class="pb-0">
                    <v-autocomplete
                                    v-model="params.thu_kho_id"
                                    :items="options.thukhos"
                                    item-text="ten"
                                    dense
                                    clearable
                                    item-value="id"
                                    :label="'Thủ kho'"
                                ></v-autocomplete>
                </v-col>
                <v-col cols="6" md="6" lg="4" class="pb-0">
                    <v-autocomplete
                                    v-model="params.nhan_vien_ban_hang_id"
                                    :items="options.nvbhs"
                                    item-text="ten"
                                    clearable
                                    dense
                                    item-value="id"
                                    :label="'NVBH'"
                                ></v-autocomplete>
                </v-col>
            </v-row>
        </v-col>
        <v-col cols="12" style="padding-top: 20px" md="4">
            <v-btn
                class="float-md-right mr-md-0"
                @click="$emit('handle-search')"
                color="primary"
                medium
                
                >{{ $t("form_search_label") }}</v-btn
            >
            <v-btn
                class="float-md-right mr-4"
                @click="$emit('handle-reset')"
                color="primary"
                >Reset</v-btn
            >
        </v-col>
    </v-row>
</template>
<script>
import DateRangePicker from "@/components/DateRangePicker";

export default {
    props: ["params", "options"],
    components: { DateRangePicker },
};
</script>
<style lang=""></style>
