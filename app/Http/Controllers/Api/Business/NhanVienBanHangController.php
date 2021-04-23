<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\NhanVienBanHangRequest;
use App\Http\Resources\NhanVienBanHangResource;
use App\Models\NhanVienBanHang;
use App\Models\System\Role;
use App\Models\System\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NhanVienBanHangController extends Controller
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
        $query = NhanVienBanHang::query();
        if ($search) {
            $query->where('sdt','ilike', '%' . $search . '%');
            $query->orWhere('ten','ilike', '%' . $search . '%');
            $query->orWhere('cmnd','ilike', '%' . $search . '%');
            $query->orWhere('email','ilike', '%' . $search . '%');
        }
        return NhanVienBanHangResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NhanVienBanHangRequest $request)
    {
        DB::beginTransaction();
        try{
         $NhanVienBanHang = NhanVienBanHang::create($request->all());
        $user = User::create([
         'name'=>$request->ten,
         'username'=>$request->sdt,
         'email'=>$request->email,
         'phone_number'=>$request->sdt,
         'password'=>bcrypt(12345678),
          'role_id'=>Role::query()->where('code','nvbh')->first()->id
        ]);
        $NhanVienBanHang->user_id = $user->id;
        $NhanVienBanHang->save();
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
    public function update(Request $request, NhanVienBanHang $nhanvienbanhang)
    {
        $nhanvienbanhang->update($request->all());
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
        return NhanVienBanHang::where('id',$id)->delete();
    }
}
