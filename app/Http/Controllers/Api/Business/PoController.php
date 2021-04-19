<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\PoNhapRequest;
use App\Http\Resources\PoNhapResource;
use App\Http\Requests\PoXuatRequest;
use App\Http\Resources\PoXuatResource;
use App\Models\PoNhap;
use App\Models\PoXuat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexNhap(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $search = $request->query('search');
        $customer_id = $request->query('customer_id');
        $supplier_id = $request->query('supplier_id');
        $date = $request->query('date', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);
        $query = PoNhap::query()->with(['product','customer','supplier']);
        if ($search) {
            $query->where('po_no','ilike', '%' . $search . '%');
        }
        $query ->where('po_date', '>=', $date[0])->where('po_date', '<=', $date[1]);
        if(isset(  $customer_id ))
            $query->where('customer_id', $customer_id);
       if(isset(  $supplier_id ))
            $query->where('supplier_id', $supplier_id);
        return PoNhapResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeNhap(PoNhapRequest $request)
    {
        $info = $request->all();
        $info['po_pending'] = $info['po_quantity'];
        PoNhap::create($info);
        return Response::created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function updateNhap(Request $request, $id)
    {
        $poNhap= PoNhap::find($id);
        $info = $request->all();
        $info['po_pending'] =  $poNhap->po_pending + $info['po_quantity'] - $poNhap->po_quantity;
        $poNhap->update($info );
        return Response::updated();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroyNhap($id)
    {
        return PoNhap::where('id',$id)->delete();
    }
    public function indexXuat(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $search = $request->query('search');
        $customer_id = $request->query('customer_id');
        $supplier_id = $request->query('supplier_id');
        $date = $request->query('date', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);
        $query = PoXuat::query()->with(['product','customer','supplier']);
        if ($search) {
            $query->where('po_no','ilike', '%' . $search . '%');
        }
        $query ->where('po_date', '>=', $date[0])->where('po_date', '<=', $date[1]);
        if(isset(  $customer_id ))
            $query->where('customer_id', $customer_id);
       if(isset(  $supplier_id ))
            $query->where('supplier_id', $supplier_id);
        return PoXuatResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeXuat(PoXuatRequest $request)
    {
        $info = $request->all();
        $info['po_pending'] = $info['po_quantity'];
        PoXuat::create($info);
        return Response::created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function updateXuat(Request $request, $id)
    {
        $poNhap= PoXuat::find($id);
        $info = $request->all();
        $info['po_pending'] =$poNhap->po_pending + $info['po_quantity'] - $poNhap->po_quantity;
        $poNhap->update($info );
        return Response::updated();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroyXuat($id)
    {
        return PoXuat::where('id',$id)->delete();
    }
}
