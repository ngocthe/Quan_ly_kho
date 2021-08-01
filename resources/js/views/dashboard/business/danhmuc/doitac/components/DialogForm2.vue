<template>
    <v-dialog v-model="showdialog2" persistent max-width="900px">
        <v-card :loading="loading">
            <v-card-title>
                <span class="headline"> Chi tiết đấu giá</span>
            </v-card-title>
            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-container>
                    <v-row>
                    <v-col cols="8" >
                         <v-autocomplete
                                    v-model=" khach_hang_id"
                                    :items="options.khachhangs"
                                    item-text="ten"
                                    dense
                                    item-value="id"
                                    :label="'Khách hàng'"
                                ></v-autocomplete>
                    </v-col>
                    <v-col cols="2" >
                         <v-btn
                              color="primary"
                              @click="
                              addKH()
                              "
                                >
                                Thêm
                                </v-btn>
                    </v-col>
                    <v-col cols="12">
                     <v-data-table
                          :headers="headers"
                          :items="chitiets"
                          disable-pagination
                          fixed-header
                          disable-sort
                          calculate-widths
                          hide-default-footer
                          disable-filtering
                          class="elevation-1"
                      >
                     </v-data-table>
                    </v-col>
                </v-row>
                    </v-container>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close()">{{
                    $t("cancel")
                }}</v-btn>
                <v-btn
                    v-if="!editing"
                    color="blue darken-1"
                    text
                    @click="createData"
                    >{{ $t("Cập nhật") }}</v-btn
                >
                <v-btn v-else color="blue darken-1" text @click="updateData">{{
                    'Cập nhật'
                }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import { store,update, addkhachang } from "@/api/business/doitac";
import dialogMixin from "@/mixins/crud/dialog";
import DatePicker from "@/components/DatePicker";

//validator import
import {} from "validator";
export default {
    props: ["type","idc","showdialog2","chitiets"],
    mixins: [dialogMixin(store, update)],
    components: { DatePicker },
    computed: {
        title() {
            return this.editing ? this.$t("edit") : this.$t("create");
        },
        

    },
    data() {
        return {
          headers:[
                { text: 'Mã', value: "ma" },
                { text: 'Khách hàng', value: "khach_hang" },
                { text: 'Số tiền', value: "gia" },

                {
                    text: this.$t("actions"),
                    value: "actions",
                    align: "center",
                    width:130
                },
            ],
            rules: {},
             dragging:false,
            khach_hang_id:null
        };
    },
     methods:{
       close(){
                     this.$emit("update:showdialog2", false);
       },
        async createData(){
            try {
                await addkhachang(this.idc, {chitiets:this.chitiets});
                this.close()

                this.reload();
            } catch (error) {
                console.log(error);
                this.loading = false;
                
            }
        },
        addKH(){
          if(this.khach_hang_id ==null){
            alert('Chưa chọn khách hàng!');
            return;
          }
              this.chitiets.push({
                  ma: (Math.floor(Math.random() * 1000000)),
                  khach_hang: this.options.khachhangs.filter((item)=>{return item.id==this.khach_hang_id})[0].ten,
                  khach_hang_id:this.khach_hang_id,
                  so_tien: 0
              }); this.khach_hang_id=null
            
        },
         async updateData() {
            try {
                await addkhachang(this.idc, {chitiets:this.chitiets});
                  this.close()
                this.reload();
            } catch (error) {
                console.log(error);
                this.loading = false;
            }
        },
    
    }
};
</script>

<style>
.dropZone {
  width: 90%;
  height: 100px;
  position: relative;
  border: 2px dashed #eee;
}

.dropZone:hover {
  border: 2px solid #2e94c4;
}

.dropZone:hover .dropZone-title {
  color: #1975A0;
}

.dropZone-info {
  color: #A8A8A8;
  position: absolute;
  top: 50%;
  width: 100%;
  transform: translate(0, -50%);
  text-align: center;
}

.dropZone-title {
  color: #787878;
}

.dropZone input {
  position: absolute;
  cursor: pointer;
  top: 0px;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
}

.dropZone-upload-limit-info {
  display: flex;
  justify-content: flex-start;
  flex-direction: column;
}

.dropZone-over {
  background: #5C5C5C;
  opacity: 0.8;
}

.dropZone-uploaded {
  width: 60%;
  height: 100px;
  position: relative;
  border: 2px dashed #eee;
}

.dropZone-uploaded-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  color: #A8A8A8;
  position: absolute;
  top: 50%;
  width: 100%;
  transform: translate(0, -50%);
  text-align: center;
}

.removeFile {
  width: 200px;
}
</style>

