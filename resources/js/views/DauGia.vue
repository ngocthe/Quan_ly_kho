<template>
<div id="app">
  <v-app id="inspire" v-if="madaugia">
    <v-card-title class="white--text" style="height:80px; background:#326b35">
          <p class="text-h6">Công ty TMDV và Môi trường Ngôi Sao Xanh</p>
          <v-spacer></v-spacer>
            <span>Liên hệ: 0964408641</span>
          <v-menu
            bottom
            left
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                dark
                icon
                v-bind="attrs"
                v-on="on"
              >
                <v-icon>mdi-account</v-icon> 
              </v-btn>
            </template>

            <v-list>
              <v-list-item
                v-for="(item, i) in menu"
                :key="i"
              >
                <v-list-item-title><v-btn color="primary" @click="madaugia=false" >{{item.title}}</v-btn></v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-card-title>
        <v-card-text style="margin-top:20px; padding:20px">
        <v-row >
            <v-col cols="12" >
             <v-row>
             <v-col md="7" xs="12" style="border:1px solid #f1f1f1;" >
               <v-carousel style="border:1px solid #f1f1f1; height:400px" >
                    <v-carousel-item
                    v-for="(item,i) in data.hinh_anhs"
                    :key="i"
                    :src="'http://daugia.mauxanhcuocsong.vn'+item"
                    
                    reverse-transition="fade-transition"
                    transition="fade-transition"
                    ></v-carousel-item>
                </v-carousel>
                <div >
                    <span v-for="(item,i) in data.hinh_anhs" style="boder:1px solid #f00; width:82px; height:82px;">
                    <img   
                           :src="'http://daugia.mauxanhcuocsong.vn'+item"
                            style="width:70px; height:70px;border:2px solid #655e5e;  margin-right:10px; margin-top:10px"
                    /></span>
                </div>
                <div>
                    <b>THÔNG TIN SẢN PHẨM</b>
                <table class="tablestyle" >
                    <thead>
                        <th>Nội dung</th>
                        <th>Thông tin</th>

                    </thead>
                    <tbody>
                    <tr>
                    <td>1. Chi tiết sản phẩm:</td>
                    <td>{{data.mo_ta}}</td>
                    </tr>
                    <tr>
                    <td>2. Thành phần:</td>
                    <td>{{data.chi_tiet}}</td>
                    </tr>
                    <tr>
                   <td>3. Tổng khối lượng xuất:</td>
                    <td>{{data.so_luong_ban}}</td>
                    </tr>
                    </tbody>
                </table>
                </div>
             </v-col>
             
             <v-col md="5" xs="12">
            <span style="font-weight:bold; color:#901515; font-size:20px">{{data.ten_san_pham}}</span>
            <table style="width:100%; border-bottom:2px solid #901515">
            <tr>
            <th style="text-align: start; font-weight:300">Mã sản phẩm: {{data.ma_san_pham}}</th>
             <th style="text-align: start;font-weight:300">Tên sản phẩm: {{data.ten_san_pham}}</th>
            </tr>
             <tr>
            <th style="text-align: start;font-weight:300">Phiên đấu giá số: {{data.ma}}</th>
             <th style="text-align: start;font-weight:300"><v-chip color="error" v-if="data.trang_thai=='dang_dien_ra'"> Đang diễn ra</v-chip>
             <v-chip color="orange" v-if="data.trang_thai=='chua_dien_ra'"> Chưa diễn ra</v-chip>
             <v-chip color="success" v-if="data.trang_thai=='da_ket_thuc'"> Đã kết thúc</v-chip>
             </th>
            </tr>
              <tr>
            <th style="text-align: start;font-weight:500">Thời gian đấu giá:</th>
            </tr>
            <tr>
            <th style="text-align: start;font-weight:300">Bắt đầu: {{data.bat_dau}}</th>
            <th style="text-align: start;font-weight:300">Kết thúc: {{data.ket_thuc}}</th>
            </tr>
             <tr>
            <th style="text-align: start;font-weight:500">Giá khởi điểm: <span style="font-size:20px; color: #0137d4">{{data.gia_khoi_diem}}</span> ({{data.dvt}})</th>
            </tr>
            </table>
           <div style="text-align:center; border:1px solid #f1f1f1; padding:20px">
            <span style="font-weight:bold; color:#12222"> KẾT QUẢ </span>
            <p style="margin-top:10px"><v-icon>mdi-trending-up</v-icon> Giá cao nhất hiện tại: <span style="font-size:25px; color: #f00">{{data.cao_nhat}} </span> (vnđ/kg)</p>
                    <vue-countdown-timer
                    style="font-weight:bold"
                            @start_callback="startCallBack('event started')"
                            @end_callback="endCallBack('event ended')"
                            :start-time="data.bat_dau"
                            :end-time="data.ket_thuc"
                            :interval="1000"
                            :start-label="'Thời gian bắt đầu'"
                            :end-label="'Thời gian còn lại'"
                            label-position="begin"
                            :end-text="'Đã kết thúc!'"
                            :day-txt="'Ngày'"
                            :hour-txt="'Giờ'"
                            :minutes-txt="'Phút'"
                            :seconds-txt="'Giây'">
                            <template slot="start-label" slot-scope="scope">
                <span style="color: red;" v-if="scope.props.startLabel !== '' && scope.props.tips && scope.props.labelPosition === 'begin'">{{scope.props.startLabel}}:</span>
                <span style="color: #901515" v-if="scope.props.endLabel !== '' && !scope.props.tips && scope.props.labelPosition === 'begin'">{{scope.props.endLabel}}:</span>
            </template>
        
            <template slot="countdown" slot-scope="scope">
                <span style="background:#757219; color:white; padding:5px; border-radius:4px">{{scope.props.days}}</span><span style="margin-left:3px">{{scope.props.dayTxt}}</span>
                <span style="background:#757219; color:white; padding:5px; border-radius:4px">{{scope.props.hours}}</span><span style="margin-left:3px">{{scope.props.hourTxt}}</span>
                <span style="background:#757219; color:white; padding:5px; border-radius:4px">{{scope.props.minutes}}</span><span style="margin-left:3px">{{scope.props.minutesTxt}}</span>
                <span style="background:#757219; color:white; padding:5px; border-radius:4px">{{scope.props.seconds}}</span><span style="margin-left:3px">{{scope.props.secondsTxt}}</span>
            </template>
        
            </vue-countdown-timer>
           </div>
           <div style="text-align:center">
           <v-btn style="margin-top:20px" @click="showDialog=true" v-if="data.trang_thai=='dang_dien_ra'">
           <v-icon>mdi-gavel</v-icon> ĐẶT GIÁ THẦU
           </v-btn>
           </div>
             </v-col>
             </v-row>

            </v-col>

        </v-row>
        </v-card-text>
         <v-dialog v-model="showDialog" persistent max-width="600px">
        <v-card :loading="loading">
            <v-card-title>
                <span class="headline" > Đặt giá thầu </span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="12">
                            <v-text-field
                                v-model="form.ma_du_thau"
                                :label="'Mã dự thầu'"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12">
                            <v-text-field
                                :label="'Số tiền'"
                                v-model="form.so_tien"
                                type="number"
                                dense
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="showDialog=false"
                    >Huỷ</v-btn
                >
                <v-btn
                    color="blue darken-1"
                    text
                    @click="dauGia"
                    >Thêm</v-btn
                >
               
            </v-card-actions>
        </v-card>
    </v-dialog>
  </v-app>
  <v-app style="background:#f1f1f1"  id="login" v-else class="fill-height dmm justify-center" tag="section">
        <v-row justify="center" >
            <v-slide-y-transition appear>
                <base-material-card
                  style="margin-top:50px"
                    max-width="100%"
                    height="500"
                    width="400"
                    class="px-5 py-3"
                >
                  

                    <v-card-text class="text-center " >
                        <div
                            class="text-center grey--text body-1 font-weight-light"
                        >
                          ĐĂNG NHẬP 
                        </div>
                        <v-form ref="form" lazy-validation v-model="valid">
                            <v-text-field
                                color="secondary"
                                v-model="form.username"
                                ref="login"
                                @keyup.enter="login"
                                :disabled="loading"
                                :label="$t('login_username')"
                                :rules="rule.nameRules"
                                prepend-icon="mdi-account"
                                class="mt-10"
                            />

                            <v-text-field
                                class="mb-8"
                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                @click:append="show = !show"
                                color="secondary"
                                @keyup.enter="login"
                                v-model="form.password"
                                :disabled="loading"
                                :rules="rule.passwordRules"
                                :label="$t('login_password')"
                                prepend-icon="mdi-lock-outline"
                                :type="show ? 'text' : 'password'"
                            />
                        </v-form>

                        <pages-btn
                            large
                            color
                            depressed
                            @click="login"
                            :loading="loading"
                            class="v-btn--text success--text"
                            >{{ $t("login_button_text") }}</pages-btn
                        >
                    </v-card-text>
                </base-material-card>
            </v-slide-y-transition>

        </v-row>
      
         
         
  </v-app>
    <div class="text-center ma-2">
 <v-snackbar v-model="snackbar" :color="snack.color" :timeout="-1" top>
            {{ snack.text }}
            <template v-slot:action="{ attrs }">
                <v-btn v-bind="attrs" dark text @click="snackbar = false">
                    Đóng
                </v-btn>
            </template>
        </v-snackbar>
  </div>
        
</div>
</template>

<script>
import VueCountdownTimer from 'vuejs-countdown-timer';
import Vue from "vue";
import { show,daugia } from "@/api/business/doitac";
Vue.use(VueCountdownTimer);

export default {
      components: {
        PagesBtn: () => import("@/layouts/pages/components/Btn")
    },
  data () {
    return {
       vertical: true,
      snack:{
          text:'',
          color:'success'
      },
      snackbar:false,
      madaugia:false,
        timerCount:10,
        showDialog:false,
        loading:false,
        form:{ma_du_thau:"",so_tien:3000},
        images:[
            {src:'https://thumuaphelieudong.com/upload/sanpham/gianhomphelieuhomnaycaonhat20205405-1611214053.jpg'},
            {src:'https://thumuaphelieutuankiet.com/upload/sanpham/thu-mua-phe-lieu-nhom---cong-ty-thu-mua-phe-lieu-gia-cao-5567.jpg'},
            {src:'https://thumuaphelieudong.com/upload/sanpham/phelieuinox4-1528183151.jpg'}
        ],
      menu: [
        { icon: 'mdi-login', title: 'Đăng nhập' },
      ],
      valid: true,
        show: false,
        data:{},
        loading: false,
        form: {
            username: localStorage.getItem("username"),
            password: localStorage.getItem("password")
        },
        rule: {
            nameRules: [v => !!v || "Bạn chưa nhập tên đăng nhập"],
            passwordRules: [v => !!v || "Bạn chưa nhập password"]
        },
        socials: [
            {
                href: "#",
                icon: "mdi-facebook"
            },
            {
                href: "#",
                icon: "mdi-twitter"
            },
            {
                href: "#",
                icon: "mdi-github"
            }
        ]
    }
  },
  watch: {

            timerCount: {
                handler(value) {

                    if (value > 0) {
                        setTimeout(() => {
                            this.timerCount--;
                        }, 1000);
                    }

                },
                immediate: true // This ensures the watcher is triggered upon creation
            }

        },
  methods: {
     login() {
            this.$refs.form.validate();
            if (!this.valid) return;
            this.loading = true;
            this.$store
                .dispatch("user/login", this.form)
                .then(homeUrl => {
                    this.loading = false;
                    localStorage.setItem("username", this.form.username);
                    localStorage.setItem("password", this.form.password);
                    window.location.reload();
                })
                .catch(error => {
                    this.loading = false;
                    if (error.response.status === 401) {
                        this.$snackbar("Sai tài khoản hoặc mật khẩu", "error");
                        this.loading = false;
                    }
                });
        },
      startCallBack: function(x) {
      console.log(x);
    },
    endCallBack: function(x) {
      console.log(x);
    },
    menuItems () {
      return this.menu
    },
    async showSP(ma){
      const {data}= await show(ma);
    this.data =data 
    this.form.dau_gia_id=data.id
    },
    async dauGia(){
      try {
                    this.loading = true;
                    await daugia(this.form);
                    this.reload();
                    this.showSP()

            } catch (error) {
                this.loading = false;
                this.snack.text='Thông tin không hợp lệ!'
                this.snack.color='red'
            this.snackbar=true
             this.showDialog=false;
            }
    },
    reload() {
            this.loading = false;
            this.snackbar=true
           
               this.snack.text='Gửi đấu giá thành công!'
                this.snack.color='red'
            this.showDialog=false;
        }
  },
  mounted(){
    if(this.$route.query.madg) this.madaugia=true
      this.showSP(this.$route.query.madg)
      
  }
  
}
</script>

<style lang="css">
.tablestyle{
width:100%}
.tablestyle th{
    background:#818484;
    padding:6px;
    border:1px solid #f1f1f1;
}
.tablestyle td{
    background:#f1f1f1;
    padding:6px;
    border:1px solid #f1f1f1;
    padding-left:10px;

}
.dmm .grow {
  display:none !important
}
</style>
