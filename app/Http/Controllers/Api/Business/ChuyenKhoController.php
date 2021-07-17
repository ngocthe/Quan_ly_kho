<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChuyenKhoRequest;
use App\Http\Resources\ChuyenKhoResource;
use App\Models\ChiTietKho;
use App\Models\ChiTietChuyenKho;
use App\Models\ChuyenKho;
use App\Models\ThuKho;
use App\Models\ThuKhoKho;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class ChuyenKhoController extends Controller
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
        $den_kho_id = $request->query('den_kho_id');
        $tu_kho_id = $request->query('tu_kho_id');
        $query = ChuyenKho::query()->with(['tuKho','denKho','nguoiTao','chitiets']);
        if ($search) {
        }
        $user = Auth::user();
        $thukho = ThuKho::query()->where('user_id',$user->id)->first();
        if(isset($thukho)){
            $khoIDs= ThuKhoKho::where('thu_kho_id',$thukho->id)->pluck('kho_id');
            $query->where(function($query) use($khoIDs){
                $query->whereIn('tu_kho_id', $khoIDs);
                $query->orWhereIn('den_kho_id', $khoIDs);

            });
        }
        if(isset( $tu_kho_id )){
            $query->where('tu_kho_id',$kho_id);
        }
        if(isset( $de_kho_id )){
            $query->where('de_kho_id',$kho_id);
        }
       
        $query->orderBy('updated_at','desc');
        return ChuyenKhoResource::collection($request->all ? $query->get(): $query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChuyenKhoRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $ChuyenKho = ChuyenKho::create([
                'ngay' => $request->ngay,
                'created_by'=>$user->id,
                'nguoi_tao_phieu' => $user->id,
                'so_phieu'=>$request->so_phieu,
                'tu_kho_id' => $request->kho_id,
                'den_kho_id' => $request->xe_id,
            ]);
            foreach($request->chitiets as $item){
                if(isset($item['phe_lieu_id']))
                   { ChiTietChuyenKho::create([
                    'chuyen_kho_id'=>$ChuyenKho->id,
                    'phe_lieu_id'=>$item['phe_lieu_id'],
                    'dvt'=>$item['dvt'],
                    'so_luong'=>$item['so_luong'],
                    ]);
                    $ctkho=ChiTietKho::where('phe_lieu_id',$item['phe_lieu_id'])->where('kho_id',$request->tu_kho_id)->first();
                        if(isset($ctkho)){
                            $ctkho->khoi_luong =  $ctkho->khoi_luong-$item['so_luong'];
                            $ctkho->save();
                        }
                        $ctkho2=ChiTietKho::where('phe_lieu_id',$item['phe_lieu_id'])->where('kho_id',$request->den_kho_id)->first();
                        if(isset($ctkho2)){
                            $ctkho2->khoi_luong =  $ctkho2->khoi_luong+$item['so_luong'];
                            $ctkho2->save();
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
        $ChuyenKho = ChuyenKho::find($id);
        DB::beginTransaction();
        try{
            foreach($ChuyenKho->chitiets as $item1){
                $ctkho_old = ChiTietKho::where('phe_lieu_id',$item1['phe_lieu_id'])->where('kho_id',$ChuyenKho->kho_id)->first();
                if(isset($ctkho_old)){
                     $ctkho_old->khoi_luong =  $ctkho_old->khoi_luong+$item1['so_luong_thuc_te'];
                        $ctkho_old->save();
                    }
            }
            ChiTietChuyenKho::where('xuat_kho_id',$ChuyenKho->id)->delete();
            foreach($info['chitiets'] as $item){
                if(isset($item['phe_lieu_id']))
                {
                ChiTietChuyenKho::create([
                 'xuat_kho_id'=>$ChuyenKho->id,
                 'phe_lieu_id'=>$item['phe_lieu_id'],
                 'dvt'=>$item['dvt'],
                'so_luong_thuc_te'=>$item['so_luong_thuc_te'],
                'so_luong_de_xuat'=>$item['so_luong_de_xuat'],
                'don_gia'=>$item['don_gia'],
                ]);
                $ctkho=ChiTietKho::where('phe_lieu_id',$item['phe_lieu_id'])->where('kho_id',$request->kho_id)->first();
                    if(isset($ctkho)){
                        $ctkho->khoi_luong =  $ctkho->khoi_luong-$item['so_luong_thuc_te'];
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

            $ChuyenKho = ChuyenKho::where('id',$id)->update([
                'ngay' => $request->ngay,
                'doi_tac_id' => $request->doi_tac_id,
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
        $ChuyenKho = ChuyenKho::find($id);
        DB::beginTransaction();
        try{
            foreach($ChuyenKho->chitiets as $item1){
                $ctkho_old = ChiTietKho::where('phe_lieu_id',$item1['phe_lieu_id'])->where('kho_id',$ChuyenKho->kho_id)->first();
                if(isset( $ctkho_old ))
                { $ctkho_old->khoi_luong =  $ctkho_old->khoi_luong+$item1['so_luong_thuc_te'];
                        $ctkho_old->save();
                    }
            }
            ChiTietChuyenKho::where('xuat_kho_id',$ChuyenKho->id)->delete();
            $ChuyenKho->delete();
            DB::commit();
            return Response::updated();
        }catch(\Exception $e){
            DB::rollBack();
            dd($e);
        }
    }
}
