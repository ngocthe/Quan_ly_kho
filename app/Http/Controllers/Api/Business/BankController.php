<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\BankRequest;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Models\RevenueExpenditure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $date = $request->query('date', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);

        $search = $request->query('search');
        $query = Bank::query();
        if ($search) {
            $query->where(
                function ($query) use ($search) {
                    $query->where(
                        'name',
                        'ilike',
                        '%' . $search . '%'
                    );
                    $query->orWhere(
                        'code',
                        'ilike',
                        '%' . $search . '%'
                    );
                    $query->orWhere(
                        'account_number',
                        'ilike',
                        '%' . $search . '%'
                    );
                }
            );
        }
        $banks = $query->get();
        foreach ($banks as $bank) {
            $bank->incurred = RevenueExpenditure::where('date', '>=', $date[0])->where('date', '<=', $date[1])->where('bank_id', $bank->id)->sum(DB::raw('amount+vat'));
        }
        return response()->json([
            'message' => "Success",
            'data' => $banks
        ], 200);
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
    public function update(BankRequest $request, Bank $bank)
    {
        $bank->update($request->all());
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
}
