<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NhapKhoRequest;
use App\Http\Resources\NhapKhoResource;
use App\Models\ChiTietKho;
use App\Models\ChiTietNhapKho;
use App\Models\NhapKho;
use Illuminate\Support\Facades\DB;

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
        $kho_id = $request->query('kho_id');
        $query = NhapKho::query()->with(['kho','khachHang','xe','chitiets']);
        if ($search) {
        }
        if(isset( $kho_id )){
            $query->where('kho_id',$kho_id);
        }
        if(isset( $khach_hang_id )){
            $query->where('khach_hang_id',$khach_hang_id);
        }
        return NhapKhoResource::collection($request->all ? $query->get(): $query->paginate($perPage));
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
            $nhapkho = NhapKho::create([
                'ngay' => $request->ngay,
                'ca' => $request->ca,
                'so_phieu' => $request->so_phieu,
                'khach_hang_id' => $request->khach_hang_id,
                'kho_id' => $request->kho_id,
                'xe_id' => $request->xe_id,
                'tai_khoan_no_id' => $request->tai_khoan_no_id,
                'tai_khoan_co_id' => $request->tai_khoan_co_id,
            ]);
            foreach($request->chitiets as $item){
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
