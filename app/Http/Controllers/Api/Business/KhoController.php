<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\KhoRequest;
use App\Http\Resources\KhoResource;
use App\Models\Kho;
use Illuminate\Http\Request;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KhoRequest $request)
    {
        Kho::create($request->all());
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
