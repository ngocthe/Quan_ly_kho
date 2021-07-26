<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NhapKhoRequest;
use App\Http\Resources\NhapKhoResource;
use App\Models\ChiTietKho;
use App\Models\ChiTietNhapKho;
use App\Models\ChiTietXuatKho;

use App\Models\NhapKho;
use App\Models\XuatKho;
use App\Models\PhanLoai;

use App\Models\Kho;
use App\Models\ThuKho;
use App\Models\ThuKhoKho;
use App\Models\ChiTietPhanLoai;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NhapKhoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $search = $request->query('search');
        $khach_hang_id = $request->query('khach_hang_id');
        $ngay = $request->query('ngay', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);

        $kho_id = $request->query('kho_id');
        $query = NhapKho::query()->where('ngay', '>=', $ngay[0])->where('ngay', '<=', $ngay[1])->with(['kho','khachHang','xe','chitiets']);
        $user = Auth::user();
        $thukho = ThuKho::query()->where('user_id',$user->id)->first();
        if(isset($thukho)){
            $khoIDs= ThuKhoKho::where('thu_kho_id',$thukho->id)->pluck('kho_id');
            $query->whereIn('kho_id', $khoIDs);
        }
        if ($search) {
        }
        if(isset( $kho_id )){
            $query->where('kho_id',$kho_id);
        }
        if(isset( $khach_hang_id )){
            $query->where('khach_hang_id',$khach_hang_id);
        }
        $query->orderBy('updated_at','desc');
        return NhapKhoResource::collection($request->all ? $query->get(): $query->paginate($perPage));
    }


    public function histories(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $search = $request->query('search');
        $khach_hang_id = $request->query('khach_hang_id');
        $phe_lieu_id = $request->query('phe_lieu_id');
        $loai = $request->query('loai');
        $ngay = $request->query('ngay', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);


        $nhapkhoQuery = NhapKho::query()->where('ngay', '>=', $ngay[0])->where('ngay', '<=', $ngay[1]);
        $phanloaiQuery = PhanLoai::query()->where('ngay', '>=', $ngay[0])->where('ngay', '<=', $ngay[1]);
        $xuatkhoQuery = XuatKho::query()->where('ngay', '>=', $ngay[0])->where('ngay', '<=', $ngay[1]);
        $user = Auth::user();
        $thukho = ThuKho::query()->where('user_id',$user->id)->first();
        if(isset($thukho)){
            $khoIDs= ThuKhoKho::where('thu_kho_id',$thukho->id)->pluck('kho_id');
            $nhapkhoQuery->whereIn('kho_id', $khoIDs);
            $xuatkhoQuery->whereIn('kho_id', $khoIDs);
            $chitietphanloais = ChiTietPhanLoai::whereIn('kho_id',$khoIDs)->pluck('phan_loai_id');
            $phanloaiQuery->where(function($phanloaiQuery) use($khoIDs, $chitietphanloais){
                $phanloaiQuery->whereIn('id',$chitietphanloais);
                $phanloaiQuery->orWhereIn('kho_id', $khoIDs);
            });
        }
        if(isset($khach_hang_id)){
            $nhapkhoQuery->where('khach_hang_id', $khach_hang_id);
            $phanloaiQuery->where('khach_hang_id', $khach_hang_id);
        }
        if(isset($kho_id)){
            $nhapkhoQuery->where('kho_id', $kho_id);
            $phanloaiQuery->where('kho_id', $kho_id);
        }

        $nhapkhos = $nhapkhoQuery->get();
        $phanloaikhos = $phanloaiQuery->get();
        $xuatkhos = $xuatkhoQuery->get();
         $data=[];
        $chitietxuatQuery= ChiTietXuatKho::whereIn('xuat_kho_id',$xuatkhos->pluck('id'));
        $chitietnhapQuery= ChiTietNhapKho::whereIn('nhap_kho_id',$nhapkhos->pluck('id'));
        if(isset($phe_lieu_id)){
            $chitietnhapQuery->where('phe_lieu_id', $phe_lieu_id);
            $chitietxuatQuery->where('phe_lieu_id', $phe_lieu_id);
        }
        $dataxuats = $chitietxuatQuery->get();
        $datanhaps = $chitietnhapQuery->get();

        foreach($datanhaps as $nhap){

            $nhapkho = $nhapkhos->where('id',$nhap->nhap_kho_id)->first();
                if((!empty($loai)&&in_array(1,$loai))||empty($loai)){
                    $data[]=[
                        'phe_lieu_id'=>$nhap->phe_lieu_id,
                        'phe_lieu'=>$nhap->pheLieu->ma,
                        'so_luong'=>$nhap->so_luong_thuc_te,
                        'khach_hang'=>$nhapkho->khachHang->ten,
                        'dvt'=>$nhap->dvt,
                        'loai'=>'Nhập mua hàng',
                        'ngay'=>$nhapkho->ngay,
                        'kho'=>$nhapkho->kho->ten,
                    ];
             }
        }
        foreach( $phanloaikhos as $pl){
            if((!empty($loai)&&in_array(3,$loai))||empty($loai)){
            if(isset($phe_lieu_id)){
                if($pl->phe_lieu_id==$phe_lieu_id){
                    $data[]=[
                        'phe_lieu_id'=>$pl->phe_lieu_id,
                        'phe_lieu'=>$pl->pheLieu->ma,
                        'so_luong'=>$pl->so_luong,
                        'khach_hang'=>$pl->khachHang->ten,
                        'dvt'=>$nhap->pheLieu->don_vi,
                        'loai'=>'Xuất phân loại',
                        'ngay'=>$pl->ngay,
                        'kho'=>$pl->kho->ten,
                    ];
                }
            }else
                    $data[]=[
                        'phe_lieu_id'=>$pl->phe_lieu_id,
                        'phe_lieu'=>$pl->pheLieu->ma,
                        'so_luong'=>$pl->so_luong,
                        'khach_hang'=>$pl->khachHang->ten,
                        'dvt'=>$nhap->pheLieu->don_vi,
                        'loai'=>'Xuất phân loại',
                        'ngay'=>$pl->ngay,
                        'kho'=>$pl->kho->ten,
                    ];
                }
            foreach($pl->chitiets as $ct){
                if((!empty($loai)&&in_array(2,$loai))||empty($loai)){
                if(isset($phe_lieu_id)){
                    if($ct->phe_lieu_id==$phe_lieu_id){
                 $data[]=[
                    'phe_lieu_id'=>$ct->phe_lieu_id,
                    'phe_lieu'=>$ct->pheLieu->ma,
                    'so_luong'=>$ct->so_luong,
                    'khach_hang'=>$pl->khachHang->ten,
                    'dvt'=>$ct->dvt,
                    'loai'=>'Nhập phân loại',
                    'ngay'=>$pl->ngay,
                    'kho'=>$ct->kho->ten,
                ];
                     }}else{
                $data[]=[
                    'phe_lieu_id'=>$ct->phe_lieu_id,
                    'phe_lieu'=>$ct->pheLieu->ma,
                    'so_luong'=>$ct->so_luong,
                    'khach_hang'=>$pl->khachHang->ten,
                    'dvt'=>$ct->dvt,
                    'loai'=>'Nhập phân loại',
                    'ngay'=>$pl->ngay,
                    'kho'=>$ct->kho->ten,
                ];
            }
        }
            }
        }
        if((!empty($loai)&&in_array(4,$loai))||empty($loai)){
        foreach($dataxuats as $nhap){

            $nhapkho = $xuatkhos->where('id',$nhap->xuat_kho_id)->first();
            $data[]=[
                'phe_lieu_id'=>$nhap->phe_lieu_id,
                'phe_lieu'=>$nhap->pheLieu->ma,
                'so_luong'=>$nhap->so_luong_thuc_te,
                'khach_hang'=>'',
                'dvt'=>$nhap->dvt,
                'loai'=>'Xuất bán hàng',
                'ngay'=>$nhapkho->ngay,
                'kho'=>$nhapkho->kho->ten,
            ];
        }
    }
    return ['data'=>$data];
}

public function nhapKhoAdmin(Request $request)
{
    $perPage = $request->query('per_page', 20);
    $search = $request->query('search');
    $khach_hang_id = $request->query('khach_hang_id');
    $phe_lieu_id = $request->query('phe_lieu_id');
    $ghi_so = $request->query('ghi_so',false);

    $ngay = $request->query('ngay', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);

    $nhapkhoQuery = NhapKho::query()->where('ngay', '>=', $ngay[0])->where('ngay', '<=', $ngay[1]);
    if(isset($khach_hang_id)){
        $nhapkhoQuery->where('khach_hang_id', $khach_hang_id);
    }
    if(isset($kho_id)){
        $nhapkhoQuery->where('kho_id', $kho_id);
    }
  
    $nhapkhos = $nhapkhoQuery->get();
     $data=[];
    $chitietnhapQuery= ChiTietNhapKho::whereIn('nhap_kho_id',$nhapkhos->pluck('id'));
    if(isset($phe_lieu_id)){
        $chitietnhapQuery->where('phe_lieu_id', $phe_lieu_id);
    }
    if($ghi_so==1){
        $chitietnhapQuery->where('ghi_so',true);
    }else{
        $chitietnhapQuery->where('ghi_so',false);

    }
    $datanhaps = $chitietnhapQuery->get();
    foreach($datanhaps as $nhap){
        $nhapkho = $nhapkhos->where('id',$nhap->nhap_kho_id)->first();
            if((!empty($loai)&&in_array(1,$loai))||empty($loai)){
                $data[]=[
                    'id'=>$nhap->id,
                    'ngay'=>$nhapkho->ngay,
                    'ca'=>$nhapkho->ca,
                    'khach_hang'=>$nhapkho->khachHang->ten,
                    'bks'=>isset($nhapkho->xe)?$nhapkho->xe->bks:null,
                    'phe_lieu'=>$nhap->pheLieu->ma,
                    'dvt'=>$nhap->dvt,
                    'so_luong_thuc_te'=>$nhap->so_luong_thuc_te,
                    'so_luong_chung_tu'=>$nhap->so_luong_chung_tu,
                    'hang_cong'=>0,
                    'hang_gui'=>isset($nhap->hang_gui)?$nhap->hang_gui:0,
                    'kho'=>$nhapkho->kho->ten,
                    'kho_id'=>$nhapkho->kho_id,
                    'khach_hang_id'=>$nhapkho->khach_hang_id,
                    'ghi_so'=>$nhap->ghi_so,


                ];
         }
    }
return ['data'=>$data];
}
function addNhapKhoAdmin(Request $request){
    $form = $request->form;
    $chitiets = $request->chitiets;
    DB::beginTransaction();
    try {
        $user = Auth::user();
        $nhapkho = NhapKho::create([
            'ngay' => $form['ngay'],
            'ca' => $form['ca'],
            'created_by'=>$user->id,
            'so_phieu' => $form['so_phieu'],
            'khach_hang_id' => $form['khach_hang_id'],
            'kho_id' => $form['kho_id'],
            'xe_id' => $form['xe_id'],
        ]);
            ChiTietNhapKho::create([
             'nhap_kho_id'=>$nhapkho->id,
             'phe_lieu_id'=>$form['phe_lieu_id'],
             'dvt'=>$form['dvt'],
            'so_luong_thuc_te'=>$form['so_luong_thuc_te'],
            'so_luong_chung_tu'=>$form['so_luong_chung_tu'],
            'hang_gui'=>$form['hang_gui'],
            ]);
            if(!empty($chitiets)){
                    $phanloai = PhanLoai::create([
                        'created_by'=>$user->id,
                        'ngay' => $form['ngay'],
                        'phe_lieu_id'=>$form['phe_lieu_id'],
                        'khach_hang_id' => $form['khach_hang_id'],
                        'kho_id' => $form['kho_id'],
                        'so_phieu' => PhanLoai::max('id')+1,
                        'nguoi_can' => null,
                        'so_luong' =>$form['so_luong_thuc_te']]);
                    foreach($chitiets as $item2){
                        if($item2['chon']==true){
                        $ctnhapkho = ChiTietNhapKho::find($item2['id']);
                        ChiTietPhanLoai::create([
                         'phan_loai_id'=>$phanloai->id,
                         'phe_lieu_id'=>$ctnhapkho['phe_lieu_id'],
                         'dvt'=>$ctnhapkho['dvt'],
                        'so_luong'=>$ctnhapkho['so_luong_thuc_te'],
                        'kho_id'=>$ctnhapkho->nhapKho->kho_id,
                        ]);
                        $ctnhapkho->delete();
                    }
                    }
                }
            $ctkho=ChiTietKho::where('phe_lieu_id',$form['phe_lieu_id'])->where('kho_id',$form['kho_id'])->first();
                if(isset($ctkho)){
                    $ctkho->khoi_luong =  $ctkho->khoi_luong+$form['so_luong_thuc_te'];
                    $ctkho->save();
                }else{
                    ChiTietKho::create([
                       'kho_id'=>$form['kho_id'],
                      'phe_lieu_id'=>$form['phe_lieu_id'],
                        'dvt'=>$form['dvt'],
                        'khoi_luong'=>$form['so_luong_thuc_te'],
                    ]);
                }

        DB::commit();
        return Response::created();
    } catch (Exception $e) {
        DB::rollBack();
        return Response::error($e->getMessage());
    }
}

public function nhapKhoAdmin2(Request $request)
{
    $perPage = $request->query('per_page', 20);
    $search = $request->query('search');
    $khach_hang_id = $request->query('khach_hang_id');
    $phe_lieu_id = $request->query('phe_lieu_id');
    $ngay = $request->query('ngay');

    $nhapkhoQuery = NhapKho::query()->where('ngay', '=', $ngay);
    if(isset($khach_hang_id)){
        $nhapkhoQuery->where('khach_hang_id', $khach_hang_id);
    }
    $nhapkhos = $nhapkhoQuery->get();
     $data=[];
    $chitietnhapQuery= ChiTietNhapKho::whereIn('nhap_kho_id',$nhapkhos->pluck('id'));
    if(isset($phe_lieu_id)){
        $chitietnhapQuery->where('phe_lieu_id', $phe_lieu_id);
    }
    $datanhaps = $chitietnhapQuery->get();
    foreach($datanhaps as $nhap){
        $nhapkho = $nhapkhos->where('id',$nhap->nhap_kho_id)->first();
            if((!empty($loai)&&in_array(1,$loai))||empty($loai)){
                $data[]=[
                    'id'=>$nhap->id,
                    'ngay'=>$nhapkho->ngay,
                    'ca'=>$nhapkho->ca,
                    'khach_hang'=>$nhapkho->khachHang->ten,
                    'bks'=>isset($nhapkho->xe)?$nhapkho->xe->bks:null,
                    'phe_lieu'=>$nhap->pheLieu->ma,
                    'dvt'=>$nhap->dvt,
                    'so_luong_thuc_te'=>$nhap->so_luong_thuc_te,
                    'kho'=>$nhapkho->kho->ten,
                    'chon'=>false
                ];
         }
    }
    return ['data'=>$data];
}

    public function nhapphanloai(Request $request)
    {
        $khach_hang_id = $request->query('khach_hang_id');
        $phe_lieu_id = $request->query('phe_lieu_id');
        $ngay = $request->query('ngay', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);

        $user = Auth::user();
        $thukho = ThuKho::query()->where('user_id',$user->id)->first();
        $phanloais = PhanLoai::query()->where('ngay', '>=', $ngay[0])->where('ngay', '<=', $ngay[1])->get();
        $query = ChiTietPhanLoai::query()->whereHas('phanLoai',function($query)use($ngay){
            $query->where('ngay', '>=', $ngay[0])->where('ngay', '<=', $ngay[1]);
        })->orderBy('duyet','desc');
        if(isset($thukho)){
            $khoIDs= ThuKhoKho::where('thu_kho_id',$thukho->id)->pluck('kho_id');
            $query->whereIn('kho_id',$khoIDs);
        }
        if(isset($khach_hang_id)){
            $query->whereHas('phanLoai',function($query)use($khach_hang_id){
                $query->where('khach_hang_id', $khach_hang_id);
            });
        }
        $chitietphanloais=$query->get();
        if($request->export){
            $file = public_path() . '/excel/NhapPhanLoai.xlsx';
            $data= $chitietphanloais->where('duyet',true);
        \Excel::load($file, function ($excel) use ($data,$thukho,$phanloais) {
            $excel->sheet('Sheet1', function ($sheet) use ($data,$thukho,$phanloais) {
                $t=2;
                $so=3502;
                foreach ($data as $key => $value) {
                    $pl = $phanloais->where('id',$value->phan_loai_id)->first();
                    $sheet->row($t+$key, [
                        '1',
                        '0',
                        $pl->ngay,
                        $pl->ngay,
                        'NK2004/'.($so+1),
                        $thukho->ten,
                        $thukho->ten,
                        $thukho->ten,
                        'Nhập kho TP nhựa xô '.$pl->khachHang->ma,
                        null,
                        null,
                        $value->pheLieu->ma,
                        $value->pheLieu->ten,
                        $value->kho->ten,
                        null,
                        '1561',
                        '15411',
                        $value->pheLieu->don_vi,
                        $value->so_phieu,
                        $value->so_luong,
                        '',
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        'CPPHANLOAI'
                    ]);
                    $sheet->cell('A' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('B' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('C' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('D' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('E' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('F' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('G' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('H' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('I' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('J' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('K' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('L' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('M' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('N' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('O' . ($key + 2), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }
            });
        })->download('xlsx');
        return [];
        dd(1);
        }
         $data=[];
            foreach( $chitietphanloais as $ct){
                $pl = $phanloais->where('id',$ct->phan_loai_id)->first();
                if(isset($phe_lieu_id)){
                    if($ct->phe_lieu_id==$phe_lieu_id){
                            $data[]=[
                                'id'=>$ct->id,
                                'duyet'=>$ct->duyet,
                                'phe_lieu_id'=>$ct->phe_lieu_id,
                                'phe_lieu'=>$ct->pheLieu->ma,
                                'so_luong'=>$ct->so_luong,
                                'khach_hang'=>$pl->khachHang->ten,
                                'dvt'=>$ct->dvt,
                                'ngay'=> $pl->ngay,
                                'kho'=>$pl->kho->ten,
                            ];
                     }
                    }else{
                        $data[]=[
                            'id'=>$ct->id,
                            'duyet'=>$ct->duyet,
                            'phe_lieu_id'=>$ct->phe_lieu_id,
                            'phe_lieu'=>$ct->pheLieu->ma,
                            'so_luong'=>$ct->so_luong,
                            'khach_hang'=> $pl->khachHang->ten,
                            'dvt'=>$ct->dvt,
                            'ngay'=> $pl->ngay,
                            'kho'=>$pl->kho->ten,
                        ];
            }
        }

       return ['data'=>$data];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NhapKhoRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $nhapkho = NhapKho::create([
                'ngay' => $request->ngay,
                'ca' => $request->ca,
                'created_by'=>$user->id,
                'so_phieu' => $request->so_phieu,
                'khach_hang_id' => $request->khach_hang_id,
                'kho_id' => $request->kho_id,
                'xe_id' => $request->xe_id,
                'tai_khoan_no_id' => $request->tai_khoan_no_id,
                'tai_khoan_co_id' => $request->tai_khoan_co_id,
            ]);
            foreach($request->chitiets as $item){
                if(isset($item['phe_lieu_id'])){
                ChiTietNhapKho::create([
                 'nhap_kho_id'=>$nhapkho->id,
                 'phe_lieu_id'=>$item['phe_lieu_id'],
                 'dvt'=>$item['dvt'],
                'so_luong_thuc_te'=>$item['so_luong_thuc_te'],
                'so_luong_chung_tu'=>$item['so_luong_chung_tu'],
                'don_gia'=>$item['don_gia'],
                ]);
                $user = Auth::user();
                // if(!empty($item['phanloais'])){
                //     if(isset($item['phanloais'][0]['phe_lieu_id'])&&($item['phanloais'][0]['so_luong']>0)){
                //         $phanloai = PhanLoai::create([
                //             'created_by'=>$user->id,
                //             'ngay' => $request->ngay,
                //             'phe_lieu_id'=>$item['phe_lieu_id'],
                //             'khach_hang_id' => $request->khach_hang_id,
                //             'kho_id' => $request->kho_id,
                //             'noi_dung' => $request->noi_dung,
                //             'so_phieu' => PhanLoai::max('id')+1,
                //             'nguoi_can' => null,
                //             'so_luong' =>$item['so_luong_thuc_te'],
            
                //         ]);
                    
                //         foreach($item['phanloais'] as $item2){
                //             if(isset($item2['phe_lieu_id']))
                //             ChiTietPhanLoai::create([
                //              'phan_loai_id'=>$phanloai->id,
                //              'phe_lieu_id'=>$item2['phe_lieu_id'],
                //              'dvt'=>$item2['dvt'],
                //             'so_luong'=>$item2['so_luong'],
                //             'kho_id'=>$request->kho_id,
                //             ]);
                                
                //         }
                //     }
                // }
                $ctkho=ChiTietKho::where('phe_lieu_id',$item['phe_lieu_id'])->where('kho_id',$request->kho_id)->first();
                    if(isset($ctkho)){
                        $ctkho->khoi_luong =  $ctkho->khoi_luong+$item['so_luong_thuc_te'];
                        $ctkho->save();
                    }else{
                        ChiTietKho::create([
                           'kho_id'=>$request->kho_id,
                          'phe_lieu_id'=>$item['phe_lieu_id'],
                            'dvt'=>$item['dvt'],
                            'khoi_luong'=>$item['so_luong_thuc_te'],
                        ]);
                    }
            }
        }

            DB::commit();
            return Response::created();
        } catch (Exception $e) {
            DB::rollBack();
            return Response::error($e->getMessage());
        }
    }

    public function phanloai($request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $phanloai = PhanLoai::create([
                'created_by'=>$user->id,
                'ngay' => $request->ngay,
                'phe_lieu_id' => $request->phe_lieu_id,
                'khach_hang_id' => $request->khach_hang_id,
                'kho_id' => $request->kho_id,
                'noi_dung' => $request->noi_dung,
                'so_phieu' => $request->so_phieu,
                'nguoi_can' => $request->nguoi_can,
                'so_luong' => $request->so_luong,

            ]);
        
            foreach($request->chitiets as $item){
                if(isset($item['phe_lieu_id'])){
                ChiTietPhanLoai::create([
                 'phan_loai_id'=>$phanloai->id,
                 'phe_lieu_id'=>$item['phe_lieu_id'],
                 'dvt'=>$item['dvt'],
                'so_luong'=>$item['so_luong'],
                'kho_id'=>$item['kho_id']
                ]);
                    
            }
        }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Response::error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $info = $request->all();
        $nhapkho = NhapKho::find($id);
        DB::beginTransaction();
        try{
            foreach($nhapkho->chitiets as $item1){
                $ctkho_old = ChiTietKho::where('phe_lieu_id',$item1['phe_lieu_id'])->where('kho_id',$nhapkho->kho_id)->first();
                $ctkho_old->khoi_luong =  $ctkho_old->khoi_luong-$item1['so_luong_thuc_te'];
                        $ctkho_old->save();
            }
            ChiTietNhapKho::where('nhap_kho_id',$nhapkho->id)->delete();
            foreach($info['chitiets'] as $item){
                if(isset($item['phe_lieu_id'])){
                ChiTietNhapKho::create([
                 'nhap_kho_id'=>$nhapkho->id,
                 'phe_lieu_id'=>$item['phe_lieu_id'],
                 'dvt'=>$item['dvt'],
                'so_luong_thuc_te'=>$item['so_luong_thuc_te'],
                'so_luong_chung_tu'=>$item['so_luong_chung_tu'],
                'don_gia'=>$item['don_gia'],
                ]);
                $ctkho=ChiTietKho::where('phe_lieu_id',$item['phe_lieu_id'])->where('kho_id',$request->kho_id)->first();
                    if(isset($ctkho)){
                        $ctkho->khoi_luong =  $ctkho->khoi_luong+$item['so_luong_thuc_te'];
                        $ctkho->save();
                    }else{
                        ChiTietKho::create([
                           'kho_id'=>$request->kho_id,
                          'phe_lieu_id'=>$item['phe_lieu_id'],
                            'dvt'=>$item['dvt'],
                            'khoi_luong'=>$item['so_luong_thuc_te'],
                        ]);
                    }
                }
            }

            $nhapkho = NhapKho::where('id',$id)->update([
                'ngay' => $request->ngay,
                'ca' => $request->ca,
                'khach_hang_id' => $request->khach_hang_id,
                'kho_id' => $request->kho_id,
                'xe_id' => $request->xe_id,
                'tai_khoan_no_id' => $request->tai_khoan_no_id,
                'tai_khoan_co_id' => $request->tai_khoan_co_id,
            ]);
            DB::commit();
            return Response::updated();
        }catch(\Exception $e){
            DB::rollBack();
            dd($e);
        }

    }
function updateSL(Request $request,$id){
    ChiTietNhapKho::where('id',$id)->update(['so_luong_chung_tu'=>$request->so_luong_chung_tu,'hang_gui'=>$request->hang_gui]);
    return Response::updated();
}
function ghiSo(Request $request){
    $data= $request->data;
    foreach($data as $item){
        ChiTietNhapKho::where('id',$item['id'])->update(['ghi_so'=>true]);
    }
    return Response::updated();
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $nhapkho = NhapKho::find($id);
        DB::beginTransaction();
        try{
            foreach($nhapkho->chitiets as $item1){
                $ctkho_old = ChiTietKho::where('phe_lieu_id',$item1['phe_lieu_id'])->where('kho_id',$nhapkho->kho_id)->first();
                $ctkho_old->khoi_luong =  $ctkho_old->khoi_luong-$item1['so_luong_thuc_te'];
                        $ctkho_old->save();
            }
            ChiTietNhapKho::where('nhap_kho_id',$nhapkho->id)->delete();
            $nhapkho->delete();
            DB::commit();
            return Response::updated();
        }catch(\Exception $e){
            DB::rollBack();
            dd($e);
        }
    }
}
