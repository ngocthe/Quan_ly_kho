<template>
<div id="app">
  <v-app id="inspire" >
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
                <v-list-item-title><v-btn color="primary" >{{item.title}}</v-btn></v-list-item-title>
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
                    v-for="(item,i) in images"
                    :key="i"
                    :src="item.src"
                    
                    reverse-transition="fade-transition"
                    transition="fade-transition"
                    ></v-carousel-item>
                </v-carousel>
                <div >
                    <span v-for="(item,i) in images" style="boder:1px solid #f00; width:82px; height:82px;">
                    <img   
                            :src="item.src"
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
                    <td>Nhôm phế liệu nguyên chất</td>
                    </tr>
                    <tr>
                    <td>2. Thành phần:</td>
                    <td>100% nhôm phế liệu nguyên chất</td>
                    </tr>
                    <tr>
                   <td>3. Tổng khối lượng xuất:</td>
                    <td>10,000 kg</td>
                    </tr>
                    </tbody>
                </table>
                </div>
             </v-col>
             
             <v-col md="5" xs="12">
            <span style="font-weight:bold; color:#901515; font-size:20px">Nhôm Phế Liệu</span>
            <table style="width:100%; border-bottom:2px solid #901515">
            <tr>
            <th style="text-align: start; font-weight:300">Mã sản phẩm: NPL</th>
             <th style="text-align: start;font-weight:300">Tên sản phẩm: Nhôm Phế Liệu</th>
            </tr>
             <tr>
            <th style="text-align: start;font-weight:300">Phiên đấu giá số: 26</th>
             <th style="text-align: start;font-weight:300"><v-chip color="orange">Đang diễn ra</v-chip></th>
            </tr>
              <tr>
            <th style="text-align: start;font-weight:500">Thời gian đấu giá:</th>
            </tr>
            <tr>
            <th style="text-align: start;font-weight:300">Bắt đầu: 15-07-2021 10:00</th>
            <th style="text-align: start;font-weight:300">Kết thúc: 01-08-2021 10:00</th>
            </tr>
             <tr>
            <th style="text-align: start;font-weight:500">Giá khởi điểm: <span style="font-size:20px; color: #0137d4">3,000</span> (vnđ/kg)</th>
            </tr>
            </table>
           <div style="text-align:center; border:1px solid #f1f1f1; padding:20px">
            <span style="font-weight:bold; color:#12222"> KẾT QUẢ </span>
            <p style="margin-top:10px"><v-icon>mdi-trending-up</v-icon> Giá cao nhất hiện tại: <span style="font-size:25px; color: #f00">3,450 </span> (vnđ/kg)</p>
                    <vue-countdown-timer
                    style="font-weight:bold"
                            @start_callback="startCallBack('event started')"
                            @end_callback="endCallBack('event ended')"
                            :start-time="'2021-07-15 00:00:00'"
                            :end-time="'2021-08-01 00:00:00'"
                            :interval="1000"
                            :start-label="'Thời gian bắt đầu'"
                            :end-label="'Thời gian còn lại'"
                            label-position="begin"
                            :end-text="'Event ended!'"
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
           <v-btn style="margin-top:20px" @click="showDialog=true">
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
                <span class="headline"> Đặt giá thầu </span>
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
                    @click="createData"
                    >Thêm</v-btn
                >
               
            </v-card-actions>
        </v-card>
    </v-dialog>
  </v-app>
</div>
</template>

<script>
import VueCountdownTimer from 'vuejs-countdown-timer';
import Vue from "vue";

Vue.use(VueCountdownTimer);

export default {
  data () {
    return {
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
      startCallBack: function(x) {
      console.log(x);
    },
    endCallBack: function(x) {
      console.log(x);
    },
    menuItems () {
      return this.menu
    }
  },
  mounted(){
    console.log(this.$route.query.madg)
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
</style>
