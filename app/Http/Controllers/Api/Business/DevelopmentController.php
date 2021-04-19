<?php

namespace App\Http\Controllers\Api\Business;


use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\DevelopmentRequest;
use App\Http\Resources\DevelopmentResource;
use App\Models\Development;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DevelopmentController extends Controller
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
        $query = Development::query()->with('customer');
        if(isset($customer_id)) $query->where('customer_id', $customer_id);
        $query->where('date', '>=', $date[0])->where('date', '<=', $date[1]);
        return DevelopmentResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DevelopmentRequest $request)
    {
        Development::create($request->all());
        return Response::created();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(DevelopmentRequest $request, Development $development)
    {
        $development->update($request->all());
        return Response::updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Development $development)
    {
        return Helper::delete($development);
    }

    public function attach(Request $request, $id)
    {
        $file = $request->file('file');
        $path = Storage::putFileAs(
            'public/development/document',$file,
            'Id_'.$id.'/'.$file->getClientOriginalName()
        );
        $document= Storage::url($path);
        $development = Development::find($id);
        $development->document = $document;
        $development->save();
        return Response::updated();
    }
}
