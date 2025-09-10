<?php

namespace App\Http\Controllers\Admin\Frontend;

// use App\Http\Controllers\Admin\Frontend\Http;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\ShiftSetting;
use Illuminate\Http\Request;

class BaseBranchController extends Controller
{
 public function index()
    { 
        
          $response = Http::withHeaders([
              'Content-Type' => 'application/json',
              'accept' => 'application/json',
          ])
              ->withOptions(['verify' => false])
              ->get('http://attendance2.localhost.com/api/branch_manage/list');
        //   dd($response->json());
          $branches  = $response->json()['branches'];
          return view('admin.frontend.branch.index',compact('branches'));
    }


    public function store(Request $request)
    {
    //   dd($request->all());
        Http::post(url('http://attendance2.localhost.com/api/branch_manage/store'), $request->all());
        return redirect()->route('branch.index');
    }

    public function edit($uid)
    {
        // dd($uid);
        $response = Http::get(url("http://attendance2.localhost.com/api/branch_manage/edit/{$uid}"));
        // dd($response->json());
        $branch = $response->json()['data'];
        return view('admin.frontend.branch.edit', compact('branch'));
    }

    public function update(Request $request, $uid)
    {
        // dd($request->all());    
        Http::put(url("http://attendance2.localhost.com/api/branch_manage/update/{$uid}"), $request->all());
        return redirect()->route('branch.index');
    }

    public function destroy($uid)
    {
        Http::delete(url("http://attendance2.localhost.com/api/branch_manage/delete/{$uid}"));
        return redirect()->route('branch.index');
    }
 
}
