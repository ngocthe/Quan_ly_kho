<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\BaoVeRequest;
use App\Http\Resources\BaoVeResource;
use App\Models\BaoVe;
use App\Models\System\Role;
use App\Models\System\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaoVeController extends Controller
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
        $query = BaoVe::query();
        if ($search) {
            $query->where('sdt','ilike', '%' . $search . '%');
            $query->orWhere('ten','ilike', '%' . $search . '%');
            $query->orWhere('cmnd','ilike', '%' . $search . '%');
        }
        return BaoVeResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BaoVeRequest $request)
    {
        DB::beginTransaction();
        try{
         $baove = BaoVe::create($request->all());
        $user = User::create([
         'name'=>$request->ten,
         'username'=>$request->sdt,
         'email'=>$request->sdt,
         'phone_number'=>$request->sdt,
         'password'=>bcrypt(12345678),
          'role_id'=>Role::query()->where('code','baove')->first()->id
        ]);
        $baove->user_id = $user->id;
        $baove->save();
        DB::commit();
        return Response::created();
        }catch(\Exception $ex){
            DB::rollback();
            dd($ex);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BaoVe $baove)
    {
        $baove->update($request->all());
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
        return BaoVe::where('id',$id)->delete();
    }
}
