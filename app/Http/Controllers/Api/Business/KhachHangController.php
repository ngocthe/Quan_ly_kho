<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\KhachHangRequest;
use App\Http\Resources\KhachHangResource;
use App\Models\KhachHang;

class KhachHangController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $search = $request->query('search');
        $query = KhachHang::query();
        if ($search) {
            $query->where('ma','ilike', '%' . $search . '%');
            $query->orWhere('ten','ilike', '%' . $search . '%');

        }
        return KhachHangResource::collection($request->all?$query->get():$query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KhachHangRequest $request)
    {
        KhachHang::create($request->all());
        return Response::created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KhachHang $khachhang)
    {
        $khachhang->update($request->all());
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
        return KhachHang::where('id',$id)->delete();
    }
}
