<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Requests\CalendarRequest;
use App\Http\Resources\CalendarResource;
use App\Models\Calendar;
use App\Models\CalendarDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start = json_decode($request->start, true);
        $end = json_decode($request->end, true);
        $query = Calendar::query()->with('calendarDetails');
        $query->where('start', '>=', Carbon::parse($start['date']))->where('start', '<=', Carbon::parse($end['date']));
        return CalendarResource::collection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CalendarRequest $request)
    {
        $info['name'] = $request->get('name');
        $info['description'] = $request->get('description');
        $user = Auth::user();
        $info['created_by'] = $user->name;
        $info['company_id'] = $request->company_id;
        switch ($user->id) {
            case 4:
                $info['color'] = '#01A9DB';
                break;
            case 6:
                $info['color'] = '#B40404';
                break;
            case 4:
                $info['color'] = 'black';
                break;
            case 7:
                $info['color'] = '#AEB404';
                break;
            case 5:
                $info['color'] = '#B404AE';
                break;
            case 3:
                $info['color'] = 'green';
                break;
            default:
                $info['color'] = '#2F0B3A';
                break;
        }
        $date = $request->get('date', [Carbon::now()->toDateString(), Carbon::now()->toDateString()]);
        $info['start'] = Carbon::parse($date[0]);
        $info['end'] = Carbon::parse($date[1]);
        Calendar::create($info);
        return Response::created();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CalendarRequest $request, Calendar $calendar)
    {
        $calendar->update($request->all());
        return Response::updated();
    }


    public function deletedetails($id){
        $user = Auth::user();
       $ca_detail=  CalendarDetail::find($id);
       if($user->name= $ca_detail->created_by){
        $ca_detail->delete();
        return response()->json([
            'message' => "Success",
            'data' => []
        ], 200);
       }
         return response()->json([
        'message' => "Can not delete note!",
        'data' => []
    ], 500);
    }
    public function addComment(Request $request, $id)
    {
        $user = Auth::user();
        $info['created_by'] = $user->name;
        switch ($user->id) {
            case 4:
                $info['color'] = '#01A9DB';
                break;
            case 6:
                $info['color'] = '#B40404';
                break;
            case 4:
                $info['color'] = 'black';
                break;
            case 7:
                $info['color'] = '#AEB404';
                break;
            case 5:
                $info['color'] = '#B404AE';
                break;
            case 3:
                $info['color'] = 'green';
                break;
            default:
                $info['color'] = '#2F0B3A';
                break;
        }
        $info['description'] = $request->get('description');
        $info['calendar_id'] = $id;

        $cd = CalendarDetail::create($info);
        return response()->json([
            'message' => "Success",
            'data' => $cd
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        $user = Auth::user();
        if ($calendar->created_by == $user->name) {
            $calendar->delete();
            return response()->json([
                'message' => "Success",
                'data' => []
            ], 200);
        } else
            return response()->json([
                'message' => "Can not delete note!",
                'data' => []
            ], 500);
    }
}
