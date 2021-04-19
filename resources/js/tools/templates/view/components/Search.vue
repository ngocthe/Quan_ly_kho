<template>
    <v-row>
        <v-col md="8" cols="12" class="pb-0">
            <v-row>
                <v-col cols="12" md="6" lg="4" class="pb-0">
                    <v-select
                        v-model="params.type"
                        :items="[
                            { id: 'S', name: $t('supplier') },
                            { id: 'C', name: $t('customer') },
                        ]"
                        item-text="name"
                        item-value="id"
                        clearable
                        :label="$t('type')"
                        @change="$emit('handle-search')"
                        outlined
                        hide-details
                        dense
                    ></v-select>
                </v-col>

                <v-col cols="12" md="6" lg="4" class="pb-0">
                    <v-text-field
                        :label="$t('form_search_label')"
                        :placeholder="$t('form_search_placeholder')"
                        clearable
                        outlined
                        hide-details
                        dense
                        @keyup.enter="$emit('handle-search')"
                        v-model="params.search"
                    ></v-text-field>
                </v-col>
            </v-row>
        </v-col>
        <v-col cols="12" md="4" style="margin-top: 12px">
            <v-btn
                class="float-md-right mr-md-0 mb-0"
                @click="$emit('handle-search')"
                color="primary"
                style="height: 40px"
                medium
                >{{ $t("form_search_label") }}</v-btn
            >
            <v-btn
                class="float-md-right mr-4 mb-0"
                style="height: 40px"
                @click="$emit('handle-reset')"
                color="primary"
                >Reset</v-btn
            >
        </v-col>
    </v-row>
</template>
<script>
export default {
    props: ["params", "options"],
};
</script>
<style lang=""></style>
