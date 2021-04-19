<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\DebtRequest;
use App\Http\Resources\DebtResource;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $reference_id = $request->query('reference_id');
        $month = $request->query('month');
        $type = $request->query('type', 1);
        $year = $request->query('year', Carbon::now()->year);
        $query = Debt::query()->with('reference');
        $query->where('type', $type);
        if(!empty(  $month )) $query->whereIn('month', $month);
        $query->where('year', $year);
        if (isset($reference_id)) {
            $query->where('reference_id', $reference_id);
        }
        return DebtResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankRequest $request)
    {
        Bank::create($request->all());
        return Response::created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Debt $debt)
    {
        $debt->update($request->all());
        return Response::updated();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        return Helper::delete($bank);
    }

    public function getYear($id)
    {
        $months = Debt::where('reference_id',$id)->whereRaw(DB::raw('amount_payment<amount_owed'))->select(DB::raw('distinct(year)'))->get();
         return response()->json([
            'message' => "Success",
            'data' => $months
        ], 200);
    }
    public function getMonth($id,$year)
    {
        $months = Debt::where('reference_id',$id)->where('year',$year)->whereRaw(DB::raw('amount_payment<amount_owed'))->select(DB::raw('distinct(month)'))->get();
         return response()->json([
            'message' => "Success",
            'data' => $months
        ], 200);
    }

    public function getAmount($id,$year,$month)
    {
        $months = Debt::where('reference_id',$id)->where('year',$year)->where('month',$month)->first();
         return response()->json([
            'message' => "Success",
            'data' => $months->amount_owed*$months->exchange_rate
        ], 200);
    }
}
