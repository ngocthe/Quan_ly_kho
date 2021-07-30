<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DauGiaRequest;
use App\Http\Resources\DauGiaResource;
use App\Models\DauGia;
use Carbon\Carbon;

use Illuminate\Support\Facades\Storage;

class DauGiaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $search = $request->query('search');
        $query = DauGia::query();
        if ($search) {
            $query->where('ma','ilike', '%' . $search . '%');

        }
        return DauGiaResource::collection($request->all?$query->get():$query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DauGiaRequest $request)
    {
        $form = $request->form;
        $form = json_decode($form,true);
        $files = $request->file();
        $hinhanhs=[];
        foreach($files as $key=> $file){
            $path = Storage::putFileAs(
                'public/attachment',
                $file,
                str_replace('/', '_', $file->getClientOriginalName())
            );
            $hinhanhs[]= Storage::url($path);
        }
        $form['hinh_anhs']=$hinhanhs;
        DauGia::create([
   "ma" => $form['ma'],
  "ma_san_pham" => $form['ma_san_pham'],
  "ten_san_pham" => $form['ten_san_pham'],
  "hinh_anhs" => $form['hinh_anhs'],
  "bat_dau" => Carbon::parse($form['bat_dau'])->startOfDay(),
  "ket_thuc" => Carbon::parse($form['ket_thuc'])->endOfDay(),
  "gia_khoi_diem" => $form['gia_khoi_diem'],
  "mo_ta" => $form['mo_ta'],
  "chi_tiet" => $form['chi_tiet'],
  "so_luong_ban" => $form['so_luong_ban'],
  "dvt" => $form['dvt'],
        ]);
        return Response::created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DauGia $DauGia)
    {
        $DauGia->update($request->all());
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
        return DauGia::where('id',$id)->delete();
    }

    public function show($ma)
    {
        return ['data'=>DauGia::where('ma',$ma)->first()];
    }
}
