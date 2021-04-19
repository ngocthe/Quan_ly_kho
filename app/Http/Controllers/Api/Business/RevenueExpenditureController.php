<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\BankRequest;
use App\Http\Requests\RevenueExpenditureRequest;
use App\Http\Resources\BankResource;
use App\Http\Resources\RevenueExpenditureResource;
use App\Models\Bank;
use App\Models\Debt;
use App\Models\Partner;
use App\Models\RevenueExpenditure;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueExpenditureController extends Controller
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
        $bank_id = $request->query('bank_id');
        $type = $request->query('type');
        $date = $request->query('date', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);

        $query = RevenueExpenditure::query()->where('date', '>=', $date[0])->where('date', '<=', $date[1])->with('bank');
        if (isset($bank_id)) {
            $query->where('bank_id', $bank_id);
        }
        if (isset($type)) {
            $query->where('type', $type);
        }
        if ($search) {
            $query->where(
                function ($query) use ($search) {
                    $query->where(
                        'description',
                        'ilike',
                        '%' . $search . '%'
                    );
                    // $query->orWhere(
                    //     'code',
                    //     'ilike',
                    //     '%' . $search . '%'
                    // );
                    // $query->orWhere(
                    //     'account_number',
                    //     'ilike',
                    //     '%' . $search . '%'
                    // );
                }
            );
        }
        $query->orderBy('date','desc');
        return RevenueExpenditureResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RevenueExpenditureRequest $request)
    {
        DB::beginTransaction();
        $type_t1 = $request->get('type_t1');
        $type_c1 = $request->get('type_c1');
        $info = $request->all();
        if (isset($type_t1) && $info['ck']) {
            $bank = Bank::find($info['bank_id']);
            $bank->balance = $bank->balance + $info['amount'];
            $bank->save();
        }
         if(isset($type_t1) && !$info['ck']){
            $setting=Setting::where('key','cash')->first();
            $setting->value= $setting->value+$info['amount'];
            $setting->save();
        }
        if (isset($type_c1) && $info['ck']) {
            $bank = Bank::find($info['bank_id']);
            $bank->balance = $bank->balance - $info['amount']-$info['ck'];
            $bank->save();
        }
         if(isset($type_c1) && !$info['ck']){
            $setting=Setting::where('key','cash')->first();
            $setting->value= $setting->value-$info['amount'];
            $setting->save(); 
        }
        if (isset($type_t1) && $type_t1 == 1) {
            $date = Carbon::parse($request->date);
            $month = $request->month;
            $year = $request->year;
            $debt = Debt::where('month', $month)->where('year', $year)->where('reference_id', $request->type_t2)->where('type', false)->first();
            if (isset($debt)) {
                $debt->update(['date_payment' => $date, 'amount_payment' => $request->amount]);
            }
            $partner =  Partner::find($request->type_t2);
            $info['description'] = $partner->name;
            $partner->debt = $partner->debt - $info['amount'];
            $partner->save();
        }
        if(isset($type_c1) && $type_c1 == 5){
            $bank_id =  $request->type_c2;
            $bank = Bank::find($bank_id);
            $bank->balance = $bank->balance + $info['amount'];
            $bank->save();
        }
        if (isset($type_c1) && $type_c1 == 3) {
            $date = Carbon::parse($request->date);
            $month = $request->month;
            $year = $request->year;
            $debt = Debt::where('month', $month)->where('year', $year)->where('reference_id', $request->type_c2)->where('type', true)->first();
            if (isset($debt)) {
                $debt->update(['date_payment' => $date, 'amount_payment' => $request->amount]);
            }
            $partner =  Partner::find($request->type_c2);
            $info['description'] = $partner->name;
            $partner->debt = $partner->debt - $info['amount'] ;
            $partner->save();
        }
        RevenueExpenditure::create($info);
        DB::commit();
        return Response::created();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(RevenueExpenditureRequest $request, RevenueExpenditure $revenueExpenditure)
    {
        $revenueExpenditure->update($request->all());
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
        DB::beginTransaction();
        $re=RevenueExpenditure::find($id);
        $type_t1 = $re->type_t1;
        $type_c1 =$re->type_c1; 
        $info = $re;
        if (isset($type_t1) && $info['ck']) {
            $bank = Bank::find($info['bank_id']);
            $bank->balance = $bank->balance - $info['amount'];
            $bank->save();
        }
         if(isset($type_t1) && !$info['ck']){
            $setting=Setting::where('key','cash')->first();
            $setting->value= $setting->value-$info['amount'];
            $setting->save();
        }
        if (isset($type_c1) && $info['ck']) {
            $bank = Bank::find($info['bank_id']);
            $bank->balance = $bank->balance + $info['amount'] +$info['fee_ck'];
            $bank->save();
        }
         if(isset($type_c1) && !$info['ck']){
            $setting=Setting::where('key','cash')->first();
            $setting->value= $setting->value+$info['amount'];
            $setting->save(); 
        }

        if (isset($type_t1) && $type_t1 == 1) {
            $date = Carbon::parse($re->date);
            $month = $re->month;
            $year = $re->year;
            $debt = Debt::where('month', $month)->where('year', $year)->where('reference_id', $re->type_t2)->where('type', false)->first();
            if (isset($debt)) {
                $debt->date_payment = null;
                $debt->amount_payment = $debt->amount_payment-$re->amount;
                $debt->save();
            }
            $partner =  Partner::find($re->type_t2);
            $info['description'] = $partner->name;
            $partner->debt = $partner->debt + $info['amount'];
            $partner->save();
        }
        if(isset($type_c1) && $type_c1 == 5){
            $bank_id =  $re->type_c2;
            $bank = Bank::find($bank_id);
            $bank->balance = $bank->balance - $re->amount;
            $bank->save();
        }
        if (isset($type_c1) && $type_c1 == 3) {
            $date = Carbon::parse($re->date);
            $month = $re->month;
            $year = $re->year;
            $debt = Debt::where('month', $month)->where('year', $year)->where('reference_id', $re->type_c2)->where('type', true)->first();
            if (isset($debt)) {
                $debt->date_payment = null;
                $debt->amount_payment = $debt->amount_payment-$re->amount;
                $debt->save();
            }
            $partner =  Partner::find($re->type_c2);
            $info['description'] = $partner->name;
            $partner->debt = $partner->debt + $info['amount'];
            $partner->save();
        }
        $re->delete();
        DB::commit();
        return Response::deleted();
    }
}
