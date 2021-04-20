<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DoiTacRequest;
use App\Http\Resources\DoiTacResource;
use App\Models\DoiTac;

class DoiTacController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $search = $request->query('search');
        $query = DoiTac::query();
        if ($search) {
            $query->where('ma','ilike', '%' . $search . '%');
            $query->orWhere('ma_misa','ilike', '%' . $search . '%');
            $query->orWhere('ten','ilike', '%' . $search . '%');

        }
        return DoiTacResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DoiTacRequest $request)
    {
        DoiTac::create($request->all());
        return Response::created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DoiTac $DoiTac)
    {
        $DoiTac->update($request->all());
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
        return DoiTac::where('id',$id)->delete();
    }
}
