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
            $phanloaiQuery->whereIn('kho_id', $khoIDs);
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
                if((isset($loai)&&in_array(3,$loai))||empty($loai)){
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
            if((isset($loai)&&in_array(3,$loai)||empty($loai)){
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
                if((isset($loai)&&in_array(2,$loai))||empty($loai)){
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
        if((isset($loai)&&in_array(4,$loai))||empty($loai)){
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
