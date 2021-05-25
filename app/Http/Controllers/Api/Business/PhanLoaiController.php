<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PhanLoaiRequest;
use App\Http\Resources\PhanLoaiResource;
use App\Models\ChiTietKho;
use App\Models\ChiTietPhanLoai;
use App\Models\PhanLoai;
use Carbon\Carbon;
use App\Models\ThuKho;
use App\Models\ThuKhoKho;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PhanLoaiController extends Controller
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
        $ngay = $request->query('ngay', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);
        $khach_hang_id = $request->query('khach_hang_id');
        $kho_id = $request->query('kho_id');
        $phe_lieu_id = $request->query('phe_lieu_id');

        $query = PhanLoai::query()->where('ngay', '>=', $ngay[0])->where('ngay', '<=', $ngay[1])->with(['kho','khachHang','chitiets']);
        if ($search) {
        }
        $user = Auth::user();
        $thukho = ThuKho::query()->where('user_id',$user->id)->first();
        if(isset($thukho)){
            $khoIDs= ThuKhoKho::where('thu_kho_id',$thukho->id)->pluck('kho_id');
            $query->whereIn('kho_id', $khoIDs);
        }
        if(isset( $kho_id )){
            $query->where('kho_id',$kho_id);
        }
        if(isset( $khach_hang_id )){
            $query->where('khach_hang_id',$khach_hang_id);
        }
        if(isset( $phe_lieu_id )){
            $query->where('phe_lieu_id',$phe_lieu_id);
        }
        return PhanLoaiResource::collection($request->all ? $query->get(): $query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PhanLoaiRequest $request)
    {
        DB::beginTransaction();
        try {
            $ctkho1= ChiTietKho::where('phe_lieu_id',$request->phe_lieu_id)->where('kho_id',$request->kho_id)->first();
            // if(!isset($ctkho1)||$ctkho1->khoi_luong<$request->so_luong){
            //   return response()->json([
            //     'code'    => 500,
            //     'message' => 'Khối lượng trong kho không đủ!',
            //     'result'  => []
            // ], 500, []);
            // }

            $phanloai = PhanLoai::create([
                'ngay' => $request->ngay,
                'phe_lieu_id' => $request->phe_lieu_id,
                'khach_hang_id' => $request->khach_hang_id,
                'kho_id' => $request->kho_id,
                'noi_dung' => $request->noi_dung,
                'so_phieu' => $request->so_phieu,
                'nguoi_can' => $request->nguoi_can,
                'so_luong' => $request->so_luong,

            ]);
            $ctkho1->khoi_luong =  $ctkho1->khoi_luong-$request->so_luong;
                        $ctkho1->save();
            foreach($request->chitiets as $item){
                if(isset($item['phe_lieu_id'])){
                ChiTietPhanLoai::create([
                 'phan_loai_id'=>$phanloai->id,
                 'phe_lieu_id'=>$item['phe_lieu_id'],
                 'dvt'=>$item['dvt'],
                'so_luong'=>$item['so_luong'],
                'kho_id'=>$item['kho_id']
                ]);
                $ctkho=ChiTietKho::where('phe_lieu_id',$item['phe_lieu_id'])->where('kho_id',$item['kho_id'])->first();
                    if(isset($ctkho)){
                        $ctkho->khoi_luong =  $ctkho->khoi_luong+$item['so_luong'];
                        $ctkho->save();
                    }else{
                        ChiTietKho::create([
                           'kho_id'=>$item['kho_id'],
                            'phe_lieu_id'=>$item['phe_lieu_id'],
                            'dvt'=>$item['dvt'],
                            'khoi_luong'=>$item['so_luong'],
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
    // public function update(Request $request, $id)
    // {
    //     $info = $request->all();
    //     $PhanLoai = PhanLoai::find($id);
    //     DB::beginTransaction();
    //     try{
    //         foreach($PhanLoai->chitiets as $item1){
    //             $ctkho_old = ChiTietKho::where('phe_lieu_id',$item1['phe_lieu_id'])->where('kho_id',$PhanLoai->kho_id)->first();
    //             $ctkho_old->khoi_luong =  $ctkho_old->khoi_luong-$item1['so_luong_thuc_te'];
    //                     $ctkho_old->save();
    //         }
    //         ChiTietPhanLoai::where('nhap_kho_id',$PhanLoai->id)->delete();
    //         foreach($info['chitiets'] as $item){
    //             ChiTietPhanLoai::create([
    //              'nhap_kho_id'=>$PhanLoai->id,
    //              'phe_lieu_id'=>$item['phe_lieu_id'],
    //              'dvt'=>$item['dvt'],
    //             'so_luong_thuc_te'=>$item['so_luong_thuc_te'],
    //             'so_luong_chung_tu'=>$item['so_luong_chung_tu'],
    //             'don_gia'=>$item['don_gia'],
    //             ]);
    //             $ctkho=ChiTietKho::where('phe_lieu_id',$item['phe_lieu_id'])->where('kho_id',$request->kho_id)->first();
    //                 if(isset($ctkho)){
    //                     $ctkho->khoi_luong =  $ctkho->khoi_luong+$item['so_luong_thuc_te'];
    //                     $ctkho->save();
    //                 }else{
    //                     ChiTietKho::create([
    //                        'kho_id'=>$request->kho_id,
    //                       'phe_lieu_id'=>$item['phe_lieu_id'],
    //                         'dvt'=>$item['dvt'],
    //                         'khoi_luong'=>$item['so_luong_thuc_te'],
    //                     ]);
    //                 }

    //         }

    //         $PhanLoai = PhanLoai::where('id',$id)->update([
    //             'ngay' => $request->ngay,
    //             'ca' => $request->ca,
    //             'khach_hang_id' => $request->khach_hang_id,
    //             'kho_id' => $request->kho_id,
    //             'xe_id' => $request->xe_id,
    //             'tai_khoan_no_id' => $request->tai_khoan_no_id,
    //             'tai_khoan_co_id' => $request->tai_khoan_co_id,
    //         ]);
    //         DB::commit();
    //         return Response::updated();
    //     }catch(\Exception $e){
    //         DB::rollBack();
    //         dd($e);
    //     }

    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $PhanLoai = PhanLoai::find($id);
        DB::beginTransaction();
        try{
            foreach($PhanLoai->chitiets as $item1){
                $ctkho_old = ChiTietKho::where('phe_lieu_id',$item1['phe_lieu_id'])->where('kho_id',$PhanLoai->kho_id)->first();
                $ctkho_old->khoi_luong =  $ctkho_old->khoi_luong-$item1['so_luong_thuc_te'];
                        $ctkho_old->save();
            }
            ChiTietPhanLoai::where('nhap_kho_id',$PhanLoai->id)->delete();
            $PhanLoai->delete();
            DB::commit();
            return Response::updated();
        }catch(\Exception $e){
            DB::rollBack();
            dd($e);
        }
    }

    public function export(Request $request)
    {
        $ngay = $request->query('ngay', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);
        
        $data = PhanLoai::where('ngay', '>=', $ngay[0])->where('ngay', '<=', $ngay[1])->get();
        $file = public_path() . '/excel/PHÂN LOẠI.xlsx';
        \Excel::load($file, function ($excel) use ($data) {
            $excel->sheet('Sheet1', function ($sheet) use ($data) {
                $t=2;
                foreach ($data as $key => $value) {
                    $sheet->row($t, [
                        $value['ngay'],
                        $value->pheLieu->ma,
                        $value->pheLieu->ten,
                        $value->pheLieu->don_vi,
                        $value->so_phieu,
                        $value->so_luong,
                        '',
                        $value->khachHang->ma,
                        $value->khachHang->ten,
                        $value->noi_dung,
                    ]);
                    foreach ($value->chitiets as $key2 => $value2) {
                        $sheet->row($t+$key2+1, [
                            $value['ngay'],
                            $value2->pheLieu->ma,
                            $value2->pheLieu->ten,
                            $value2->dvt,
                            $value->so_phieu,
                            '',
                            $value2->so_luong,
                            $value->khachHang->ma,
                            $value->khachHang->ten,
                            $value->noi_dung,
                        ]);
                    }
                    $t= $t +$value->chitiets->count()+1;

                    $sheet->cell('A' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('B' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('C' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('D' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('E' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('F' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('G' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('H' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('I' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('J' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('K' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('L' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('M' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('N' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('O' . ($key + 4), function ($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }
            });
        })->download('xlsx');
    }
}
