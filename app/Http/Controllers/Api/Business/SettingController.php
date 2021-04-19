<?php

namespace App\Http\Controllers\Api\Business;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ['data' => $request->exchange_rate ? Setting::where('key', 'exchange_rate')->first() : Setting::orderBy('id')->get()];
    }
    public function update(Setting $setting, Request $request)
    {
        $setting->update(['value' => $request->value]);
        return Response::updated();
    }
}
