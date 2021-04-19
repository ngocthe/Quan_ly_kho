<template>
    <v-dialog v-model="showDialog" persistent max-width="600px">
        <v-card :loading="loading">
            <v-card-title>
                <span class="headline">{{ title }}</span>
            </v-card-title>
            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    v-model="form.name"
                                    :label="$t('name')"
                                    dense
                                    :rules="[(v) => !!v || 'Item is required']"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    :label="$t('login_username')"
                                    v-model="form.username"
                                    dense
                                    :rules="[(v) => !!v || 'Item is required']"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    label="Email*"
                                    v-model="form.email"
                                    dense
                                    :rules="rules.email"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    :label="$t('phone')"
                                    v-model="form.phone_number"
                                    :rules="[(v) => !!v || 'Item is required']"
                                    dense
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row v-if="!editing">
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    :label="$t('login_password')"
                                    v-model="form.password"
                                    :rules="[(v) => !!v || 'Item is required']"
                                    dense
                                    type="password"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    :label="$t('login_password_confirm')"
                                    type="password"
                                    v-model="form.password_confirmation"
                                    :rules="[
                                        (v) => !!v || 'Item is required.',
                                        (v) =>
                                            v === form.password ||
                                            'Invalid password',
                                    ]"
                                    dense
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" sm="6">
                                <v-switch
                                    v-model="form.active"
                                    :label="$t('active')"
                                ></v-switch>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-select
                                    v-model="form.role_id"
                                    :items="options.roles"
                                    item-text="name"
                                    item-value="id"
                                    :rules="[(v) => !!v || 'Item is required']"
                                    :label="$t('role')"
                                    single-line
                                ></v-select>
                            </v-col>
                        </v-row>
                           <v-row>
                            <v-col cols="12" sm="6">
                                <v-select
                                    v-model="form.company_id"
                                    :items="[{'id':1,'name':'SD CHEMICAL VINA'},{'id':2,'name':'PHUCLINH'}]"
                                    item-text="name"
                                    item-value="id"
                                    :rules="[(v) => !!v || 'Item is required']"
                                    :label="$t('role')"
                                    single-line
                                ></v-select>
                            </v-col>
                        </v-row> 
                         </v-container
                ></v-form>
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
                    @click="createData"
                    >Thêm</v-btn
                >
                <v-btn v-else color="blue darken-1" text @click="updateData"
                    >Cập nhật</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import { store, update } from "@/api/system/user";
import dialogMixin from "@/mixins/crud/dialog";
//validator import
import { isEmail } from "validator";
export default {
    mixins: [dialogMixin(store, update)],
    computed: {
        title() {
            return this.editing ? "Sửa người dùng" : "Thêm người dùng";
        },
    },
    data() {
        return {
            rules: {
                email: [
                    (v) => !!v || "Item is required.",
                    (v) => isEmail(v) || "Invalid Email",
                ],
            },
        };
    },
};
</script>
