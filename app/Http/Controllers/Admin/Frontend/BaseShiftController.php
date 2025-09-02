<?php

namespace App\Http\Controllers\Admin\Frontend;

// use App\Http\Controllers\Admin\Frontend\Http;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\ShiftSetting;
use Illuminate\Http\Request;

class BaseShiftController extends Controller
{
 public function index()
    {
          $response = Http::withHeaders([
              'Content-Type' => 'application/json',
              'accept' => 'application/json',
          ])
              ->withOptions(['verify' => false])
              ->get('http://attendance2.localhost.com/api/shift_manage/list');

          $shifts = $response->json()['shift'];
          return view('admin.frontend.shift.index',compact('shifts'));
    }


    public function store(Request $request)
    {
      dd($request->all());
        Http::post(url('/api/shift_manage/store'), $request->all());
        return redirect()->route('admin.frontend.shift.index');
    }

    public function edit($id)
    {
        $response = Http::get(url("http://attendance2.localhost.com/api/shift_manage/edit/{$id}"));
        $shift = $response->json()['shift'];
        return view('admin.frontend.shift.edit', compact('shift'));
    }

    public function update(Request $request, $id)
    {
        Http::put(url("http://attendance2.localhost.com/api/shift_manage/update/{$id}"), $request->all());
        return redirect()->route('shift.index');
    }

    public function destroy($id)
    {
        Http::delete(url("http://attendance2.localhost.com/api/shift_manage/delete/{$id}"));
        return redirect()->route('shift.index');
    }
 
}
