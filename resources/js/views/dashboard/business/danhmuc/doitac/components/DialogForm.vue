<template>
    <v-dialog v-model="showDialog" persistent max-width="1200px">
        <v-card :loading="loading">
            <v-card-title>
                <span class="headline"> Tạo phiên đấu giá</span>
            </v-card-title>
            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-container>
                        <v-row>
                         <v-col cols="12" sm="4" @dragenter="dragging = true" >
                                <div >
                                    <div :class="['dropZone', dragging ? 'dropZone-over' : '']" @dragenter="dragging = true" @dragleave="dragging = false">
                                    <div class="dropZone-info" @drag="onChange">
                                        <span class="fa fa-cloud-upload dropZone-title"></span>
                                        <span class="dropZone-title">Kéo thả hoặc chọn hình ảnh để upload</span>
                                        <div class="dropZone-upload-limit-info">
                                        <div>maximum file size: 5 MB</div>
                                        </div>
                                    </div>
                            
                                    <input type="file" multiple @change="onChange"     accept="image/*">
                                    </div>
                                </div>
                                <ul v-for="(file,f) in files" :key="f">
                            <li>
                            <v-row>
                            <v-col cols="12" sm="8">
                            {{file.name}}
                            </v-col>
                             <v-col cols="12" sm="2" >
                                 <v-btn
                                x-small
                                @click="files.splice(f,1)"
                                class="ml-2"
                                fab
                                dark
                                color="error"
                                >
                           <v-icon dark>mdi-delete</v-icon>
                            </v-btn>
                             </v-col>
                            </v-row>
                        </li>
                        </ul>
                                
                            </v-col>
                          <v-col cols="12" sm="8">
                            <v-row>
                            <v-col cols="6" sm="3">
                              <v-text-field
                                    v-model="form.ma"
                                    :label="'Mã phiên đấu giá'"
                                    dense
                                ></v-text-field>
                            </v-col>

                            <v-col cols="6" sm="3">
                                <v-text-field
                                    v-model="form.ma_san_pham"
                                    :label="'Mã sản phẩm'"
                                    dense
                                ></v-text-field>
                            </v-col>

                              <v-col cols="6" sm="3">
                                <v-text-field
                                    v-model="form.ten_san_pham"
                                    :label="'Tên sản phẩm'"
                                    dense
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6" sm="3">
                                <v-text-field
                                    v-model="form.so_luong_ban"
                                    :label="'Số lượng bán'"
                                    dense
                                ></v-text-field>
                            </v-col>
                             <v-col cols="6" sm="3">
                                <DatePicker
                                    label="Ngày bắt đầu"
                                    :value="form.bat_dau"
                                    @updatevalue="form.bat_dau=$event"
                                />
                            </v-col>
                             <v-col cols="6" sm="3">
                                <DatePicker
                                    label="Ngày kết thúc"
                                    :value="form.ket_thuc"
                                    @updatevalue="form.ket_thuc=$event"
                                />
                            </v-col>
                              <v-col cols="6" sm="3">
                                <v-text-field
                                type="number"
                                    v-model="form.gia_khoi_diem"
                                    :label="'Giá khởi điểm'"
                                    dense
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6" sm="2">
                                <v-text-field
                                    v-model="form.dvt"
                                    :label="'Đơn vị'"
                                    dense
                                ></v-text-field>
                                
                            </v-col>
                            
                             
                              
                            <v-col cols="6" sm="4">
                                <v-textarea
                                 outlined
                                    v-model="form.mo_ta"
                                    :label="'Mô tả sản phẩm'"
                                    dense
                                ></v-textarea>
                            </v-col>
                            <v-col cols="6" sm="4">
                                <v-textarea
                                 outlined
                                    v-model="form.chi_tiet"
                                    :label="'Chi tiết sản phẩm'"
                                    dense
                                ></v-textarea>
                            </v-col>
                             
                             </v-row></v-col>

                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDialog">{{
                    $t("cancel")
                }}</v-btn>
                <v-btn
                    v-if="!editing"
                    color="blue darken-1"
                    text
                    @click="createData"
                    >{{ $t("create") }}</v-btn
                >
                <v-btn v-else color="blue darken-1" text @click="updateData">{{
                    $t("edit")
                }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import { store, update } from "@/api/business/doitac";
import dialogMixin from "@/mixins/crud/dialog";
import DatePicker from "@/components/DatePicker";

//validator import
import {} from "validator";
export default {
    props: ["type"],
    mixins: [dialogMixin(store, update)],
    components: { DatePicker },
    computed: {
        title() {
            return this.editing ? this.$t("edit") : this.$t("create");
        },
    },
    data() {
        return {
            rules: {},
             dragging:false,
          files:[],
          file:'',
        a:[],
        };
    },
     methods:{
 onChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) {
        this.dragging = false;
        return;
      }
      this.createFile(files[0]);
      if(files[1]) this.createFile(files[1]);
      if(files[2]) this.createFile(files[2]);
      if(files[3]) this.createFile(files[3]);
     if(files[4]) this.createFile(files[4]);
    },
    createFile(file) {
    
      if (file.size > 5000000) {
        alert('please check file size no over 5 MB.')
        this.dragging = false;
        return;
      }
      
      this.files.push(file);
      this.dragging = false;
    },
    removeFile() {
      this.file = '';
    },

        async createData(){
            var contrl =this
                const formData = new FormData();
                    this.files.forEach((item,index)=>{
                        console.log( JSON.stringify(item.tiets))
                         formData.append("form", JSON.stringify(this.form));
                         formData.append("file"+index, item);
                    })
                         await  store(formData);
                          this.files=[]
                           this.dragging = false;

                         this.reload()
        },
         async updateData() {
            try {
                await this.$refs.form.validate();
                if (!this.valid) return;
                if (!this.file) {
                    this.$snackbar("Please upload a pdf file", "error");
                    return;
                }
                const formData = new FormData();
                formData.append("file", this.file);
                formData.append("type", this.type);
                this.loading = true;
                await attach(this.productId, formData);
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

