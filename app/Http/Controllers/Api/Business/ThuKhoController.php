<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\ThuKhoRequest;
use App\Http\Resources\ThuKhoResource;
use App\Models\ThuKho;
use App\Models\ThuKhoKho;

use App\Models\System\Role;
use App\Models\System\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThuKhoController extends Controller
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
        $query = ThuKho::query()->with('khos');
        if ($search) {
            $query->where('sdt','ilike', '%' . $search . '%');
            $query->orWhere('ten','ilike', '%' . $search . '%');
            $query->orWhere('cmnd','ilike', '%' . $search . '%');
            $query->orWhere('email','ilike', '%' . $search . '%');
        }
        return ThuKhoResource::collection($request->all ?  $query->get():$query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ThuKhoRequest $request)
    {
        DB::beginTransaction();
        try{
         $ThuKho = ThuKho::create([
            'cmnd' => $request->cmnd,
            'email' => $request->email,
            'ten' => $request->ten,
            'sdt'=>$request->sdt,
         ]);
         foreach($request->kho_ids as $kho_id){
             ThuKhoKho::create(['kho_id'=>$kho_id,'thu_kho_id'=>$ThuKho->id]);
         } 
         
        $user = User::create([
         'name'=>$request->ten,
         'username'=>$request->sdt,
         'email'=>$request->email,
         'phone_number'=>$request->sdt,
         'password'=>bcrypt(12345678),
          'role_id'=>Role::query()->where('code','thukho')->first()->id
        ]);
        $ThuKho->user_id = $user->id;
        $ThuKho->save();
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
    public function update(Request $request, $id)
    {
       ThuKho::where('id',$id)->update([
            'cmnd' => $request->cmnd,
            'email' => $request->email,
            'ten' => $request->ten,
            'sdt'=>$request->sdt,
         ]);
         foreach($request->kho_ids as $kho_id){
             ThuKhoKho::create(['kho_id'=>$kho_id,'thu_kho_id'=>$id]);
         } 
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
        return ThuKho::where('id',$id)->delete();
    }
}
