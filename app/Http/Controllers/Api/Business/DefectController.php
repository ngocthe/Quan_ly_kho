<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\DefectRequest;
use App\Http\Resources\DefectResource;
use App\Models\Defect;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;


class DefectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $customer_id = $request->query('customer_id');
        $date = $request->query('date', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);
        $query = Defect::query()->with('customer');
        if(isset($customer_id)) $query->where('customer_id', $customer_id);
        $query->where('date', '>=', $date[0])->where('date', '<=', $date[1]);
        return DefectResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DefectRequest $request)
    {
        Defect::create($request->all());
        return Response::created();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(DefectRequest $request, Defect $defect)
    {
        $defect->update($request->all());
        return Response::updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Defect $defect)
    {
        return Helper::delete($defect);
    }

    public function attach(Request $request, $id)
    {
        $file = $request->file('file');
        $path = Storage::putFileAs(
            'public/defect/document',$file,
            'Id_'.$id.'/'.$file->getClientOriginalName()
        );
        $document= Storage::url($path);
        $defect = Defect::find($id);
        $defect->document = $document;
        $defect->save();
        return Response::updated();
    }
}
