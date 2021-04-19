<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create(['key' => 'exchange_rate', 'name' => 'Tỷ giá/Exchange Rate', 'value' => '23000']);
        Setting::create(['key' => 'company', 'name' => "Tên doanh nghiệp/Company's name", 'value' => 'CÔNG TY TNHH SD CHEMICAL VINA']);
        Setting::create(['key' => 'address', 'name' => "Địa chỉ/Address", 'value' => 'Số 33 Lý Thái Tổ, Phường Ninh Xá, Thành phố Bắc Ninh, tỉnh Bắc Ninh, Việt Nam']);
        Setting::create(['key' => 'tax_code', 'name' => "Mã số thuế/Tax Code", 'value' => '2300943023']);
        Setting::create(['key' => 'director', 'name' => "Giám đốc/Director", 'value' => 'JUNG SANG DEOK']);
        Setting::create(['key' => 'manager', 'name' => "Quản lý/Manager", 'value' => 'Trần Thị Lan Thương']);
        Setting::create(['key' => 'accountant', 'name' => "Kế toán viên/Accountant", 'value' => 'Nguyễn Thị Nguyệt']);
        Setting::create(['key' => 'stockkeeper', 'name' => "Thủ kho/Stockkeeper", 'value' => 'Nguyễn Văn Huy']);
        Setting::create(['key' => 'emails', 'name' => "Email nhận nhắc nhở/Email to receive reminders", 'value' => 'dungnknd97@gmail.com']);
    }
}
