<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Group;

class BaseSpecialWorkingdayController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])
            ->withOptions(['verify' => false])
            ->get('http://attendance2.localhost.com/api/special_working_day/list');

        // $special_working_days = $response->json()['data']['data'];
        $special_working_days = $response->json()['data']['data'] ?? [];    

        // dd($special_working_days[0]['date']);

        return view('admin.frontend.special_working_day.index', compact('special_working_days'));
    }

    public function add()
    {
         $allGroup=Group::where('status', 1)->get();
        // dd($specialWorkingDay);  
        return view('admin.frontend.special_working_day.create', compact('allGroup'));
    }
    public function edit($id)
    {

          $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])
            ->withOptions(['verify' => false])
            ->get('http://attendance2.localhost.com/api/special_working_day/edit/'.$id);

        $specialWorkingDay = $response->json()['data'];
        

        // dd($specialWorkingDay);  
        return view('admin.frontend.special_working_day.edit', compact('specialWorkingDay'));
    }

public function destroy($id)
{
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'accept' => 'application/json',
    ])
    ->withOptions(['verify' => false])
    ->delete('http://attendance2.localhost.com/api/special_working_day/delete/' . $id);

    if ($response->failed()) {
        return redirect()->back()->with('error', 'Failed to delete special workday.');
    }

    return redirect()->route('special_working_day.index')->with('success', 'Special workday deleted successfully.');
}

}
