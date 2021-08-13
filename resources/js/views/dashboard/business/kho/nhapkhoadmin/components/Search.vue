<template>
    <v-row>
        <v-col md="10" cols="12" class="pb-0">
            <v-row>
                 <v-col cols="12" md="4" lg="4" class="pb-0">
                    <DateRangePicker :value.sync="params.ngay"
                      @change="$emit('handle-search')"
                /></v-col>
                <!-- <v-col cols="6" md="6" lg="4" class="pb-0">
                    <v-text-field
                        :label="$t('form_search_label')"
                        :placeholder="$t('form_search_placeholder')"
                        clearable
                          @keyup.enter="$emit('handle-search')"
                        outlined
                        dense
                        v-model="params.search"
                    ></v-text-field>
                </v-col> -->
               
                <v-col cols="6" md="3" lg="3" class="pb-0">
                    <v-autocomplete
                                    v-model="params.khach_hang_id"
                                    @change="$emit('handle-search')"
                                    :items="options.khachhangs"
                                    item-text="ten"
                                    clearable
                                    dense
                                    item-value="id"
                                    :label="'Khách hàng'"
                                ></v-autocomplete>
                </v-col>
                 <v-col cols="6" md="3" lg="3" class="pb-0">
                    <v-autocomplete
                        v-model="params.kho_id"
                            @change="$emit('handle-search')"
                        :items="options.khos"
                        item-text="ten"
                        dense
                        clearable
                        item-value="id"
                        :label="'Kho'"
                    ></v-autocomplete>
                </v-col>
                <v-col cols="6" md="3" lg="3" class="pb-0">
                    <v-autocomplete
                        v-model="params.ghi_so"
                            @change="$emit('handle-search')"
                        :items="[{id:0,ten:'Chưa ghi sổ'},{id:1,ten:'Đã ghi sổ'}]"
                        item-text="ten"
                        dense
                        clearable
                        item-value="id"
                        :label="'Trạng thái'"
                    ></v-autocomplete>
                </v-col>
              <v-col cols="3" md="2" lg="2" class="pb-0">
                 <v-text-field type="number" dense style="!important" v-model="so" label="Số chứng từ tiếp theo"></v-text-field>
              </v-col>
        <v-col cols="6" md="3" lg="3" class="pb-0">
                <v-btn
                    @click="$emit('handle-export',so)"
                    class="mx-2"
                    small
                    dark
                    color="indigo"
                >
                    <v-icon dark>mdi-download</v-icon>
                    Xuất theo mẫu misa
                </v-btn>
              </v-col>
            </v-row>
        </v-col>
        <v-col cols="12" style="padding-top: 20px" md="2">
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
     data: () => ({
         so:0
     })

};
</script>
<style lang=""></style>
