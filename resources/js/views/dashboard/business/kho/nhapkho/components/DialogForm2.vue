<template>
    <v-dialog v-model="showupload" persistent max-width="700px">
        <v-card >
            <v-card-title>
                <span class="headline">Setup - System</span>
            </v-card-title>
            <v-card-text>
                <v-form ref="form">
                    <v-container>
                        <v-row>
                        <v-col cols="12" sm="6">
                                <v-autocomplete
                                v-model="kho_id"
                                :items="options.khos"
                                item-text="ten"
                                item-value="id"
                                style="width:100%"
                                dense
                            ></v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-file-input
                                    show-size
                                    label="Chọn file"
                                        chips color="pink"
                                        v-model="files"
                                ></v-file-input>
                            </v-col>
                        </v-row>
                        <v-row>
                        <v-col cols="12" sm="6">
                                        <v-btn
                                            outlined
                                            @click="download"
                                            color="success"
                                        >
                                        <v-icon >mdi-download</v-icon> {{ $t('download_template') }}
                                    </v-btn>
                            </v-col>
                             <v-col cols="12" sm="6">
                                        <v-btn
                                        :disabled="disabled"
                                            style="float:right; margin-right:20px"
                                            @click="uploadFile()"
                                            color="success"
                                        >
                                        <v-icon >mdi-check</v-icon> OK
                                        </v-btn>
                                        <v-btn
                                            style="float:right; margin-right:20px"
                                            @click="$emit('closeupload')"
                                            color="default"
                                        >
                                       Cancel
                                    </v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>
            <v-card-actions>

            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>

//validator import
import {} from "validator";
import { attach } from "@/api/business/nhapkho";

export default {
    props: ["showupload","form","options"],
    data() {
        return {
            files:null,
            kho_id:null,
            disabled:false
        };
    },
    methods:{
         download() {
            window.open("/api/template", "_blank");
        },
        async uploadFile(){
            try{
                this.disabled=true
               this.$loader(true);
                        const formData = new FormData();
                         formData.append("file", this.files);
                         formData.append("kho_id", this.kho_id);
                         await  attach(formData);
                             this.$loader(false);
                            this.disabled=false
                         this.$emit('closeupload')
                         }catch(error){

                }
        },
                  
    }
};
</script>
