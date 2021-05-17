<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\KhoRequest;
use App\Http\Resources\KhoResource;
use App\Models\Kho;
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
        $curl = curl_init();
        $ngay = $request->query('ngay', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://mauxanhcuocsong.vn/api/xuonghang_admin?ngay%5B%5D='. $ngay[0].'&ngay%5B%5D='. $ngay[1].'',
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
        return ["data"=>$data['result']['data'],"meta"=>$data['result']];
    }

    public function duyetXuongHang($id){
            $user =Auth::user();
            
$curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://mauxanhcuocsong.vn/api/xuonghang_duyet/'.$id.'?nguoi_duyet='.$user->name,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS =>'{
            "nguoi_duyet" : "asc"
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
    public function update(Request $request, Kho $kho)
    {
        $kho->update($request->all());
        return Response::updated();
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
