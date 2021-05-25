<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\KhoRequest;
use App\Http\Resources\KhoResource;
use App\Models\Kho;
use App\Models\ThuKho;
use App\Models\ThuKhoKho;

use App\Models\ChiTietKho;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KhoController extends Controller
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
        $nvbh_id = $request->query('nhan_vien_ban_hang_id');
        $thu_kho_id = $request->query('thu_kho_id');
        $query = Kho::query()->with(['thuKho','nvbh','chitiets']);
        $user = Auth::user();
        $thukho = ThuKho::query()->where('user_id',$user->id)->first();
        if(isset($thukho)){
            $khoIDs= ThuKhoKho::where('thu_kho_id',$thukho->id)->pluck('kho_id');
            $query->whereIn('id', $khoIDs);
        }
        if ($search) {
            $query->where('ma','ilike', '%' . $search . '%');
            $query->orWhere('ten','ilike', '%' . $search . '%');

        }
        if(isset( $thu_kho_id )){
            $query->where('thu_kho_id',$thu_kho_id);
        }
        if(isset( $nvbh_id )){
            $query->where('nhan_vien_ban_hang_id',$nvbh_id);
        }
        return KhoResource::collection($request->all ? $query->get(): $query->paginate($perPage));
    }


    function xuongHang(Request $request){
        $search = $request->query('search');
        $page = $request->query('page');
        $per_page = $request->query('per_page');
        $user = Auth::user();
        $thukho = ThuKho::query()->where('user_id',$user->id)->first();
        $kho_url='';
        $kho_id=[];
        if(isset($thukho)){
            $kho_ids=ThuKhoKho::where('thu_kho_id',$thukho->id)->pluck('kho_id');
            $kho_id = Kho::whereIn('id',$kho_ids)->pluck('kho_link_id');
            foreach($kho_id as $i){
                $e =$i."";
                $kho_url=  $kho_url.'&kho_id[]='.$e.'';
            }
        }
        $curl = curl_init();
        $ngay = $request->query('ngay', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://mauxanhcuocsong.vn/api/xuonghang_admin?ngay%5B%5D='. $ngay[0].'&ngay%5B%5D='. $ngay[1].'&search='.$search.'&page='.$page.'&per_page='.$per_page.''.$kho_url.'',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                    ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data= json_decode($response,true);
        $c=[];
        foreach($data['result']['data'] as $item){
            $t=false;
            foreach($item['kho_xuong_hangs'] as $it){
                if(in_array($it['kho_id'],$kho_id->toArray())){
                    $t= $it['duyet'];
                }
            }
            $item['duyet'] =  $t;
            $c[]=$item;
        }
        return ["data"=>$c,"meta"=>$data['result']];
    }

    public function duyetXuongHang($id){
    
            $user = Auth::user();
            $thukho = ThuKho::query()->where('user_id',$user->id)->first();
            $kho_id=[];
            if(isset($thukho)){
                $kho_ids=ThuKhoKho::where('thu_kho_id',$thukho->id)->pluck('kho_id');
                $kho_id = Kho::whereIn('id',$kho_ids)->pluck('kho_link_id');
            }
            $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://mauxanhcuocsong.vn/api/xuonghang_duyet/'.$id.'',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS =>'{
            "nguoi_duyet" : "'.$user->name.'",
            "kho_id" : "'.$kho_id.'"
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);
        return$response;
        

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KhoRequest $request)
    {
        Kho::create(['ma'=>$request->ma,'ten'=>$request->ten]);
        return Response::created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $nhapkho = Kho::where('id',$id)->update([
                'ma' => $request->ma,
                'ten' => $request->ten,
                'dia_chi' => $request->dia_chi,

            ]);
            ChiTietKho::where('kho_id',$id)->delete();
            foreach($request->chitiets as $item){
                        ChiTietKho::create([
                           'kho_id'=>$id,
                          'phe_lieu_id'=>$item['phe_lieu_id'],
                            'dvt'=>$item['dvt'],
                            'khoi_luong'=>$item['khoi_luong'],
                        ]);
            }

            DB::commit();
            return Response::updated();
        } catch (Exception $e) {
            DB::rollBack();
            return Response::error($e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Kho::where('id',$id)->delete();
    }
}
