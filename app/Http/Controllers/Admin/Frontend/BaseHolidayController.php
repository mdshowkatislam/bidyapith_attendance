<?php

namespace App\Http\Controllers\Admin\Frontend;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseHolidayController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])->withOptions(['verify' => false])
          ->get('http://attendance2.localhost.com/api/holiday_manage/list');

        $holidays = $response->json();
        return view('admin.frontend.holiday.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        Http::post(url('/api/holiday_manage/store'), $request->all());
        return redirect()->route('holiday.index');
    }

    public function edit($id)
    {  
        $response = Http::get(url("http://attendance2.localhost.com/api/holiday_manage/show/{$id}"));

        $holiday =(object) $response->json();
     
        return view('admin.frontend.holiday.form', compact('holiday'));
    }
 
    public function update(Request $request, $id)
    {
        Http::put(url("http://attendance2.localhost.com/api/holiday_manage/update/{$id}"), $request->all());
        return redirect()->route('holiday.index');
    }

    public function destroy($id)
    {
        Http::delete(url("http://attendance2.localhost.com/api/holiday_manage/delete/{$id}"));
        return redirect()->route('holiday.index');
    }
}
