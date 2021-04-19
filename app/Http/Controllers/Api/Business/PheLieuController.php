<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\PheLieuRequest;
use App\Http\Resources\PheLieuResource;
use App\Models\PheLieu;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PheLieuController extends Controller
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
        $query = PheLieu::query();
        if ($search) {
            $query->where('ten','ilike', '%' . $search . '%');
            $query->orWhere('ma','ilike', '%' . $search . '%');

        }
        return PheLieuResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PheLieuRequest $request)
    {
        PheLieu::create($request->all());
        return Response::created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PheLieu $phelieu)
    {
        $phelieu->update($request->all());
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
        return PheLieu::where('id',$id)->delete();
    }
}
