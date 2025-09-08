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
          dd($response->json());
          $branch  = $response->json()['branch'];
          return view('admin.frontend.branch.index',compact('branch'));
    }


    public function store(Request $request)
    {
      dd($request->all());
        Http::post(url('/api/branch_manage/store'), $request->all());
        return redirect()->route('admin.frontend.branch.index');
    }

    public function edit($id)
    {
        $response = Http::get(url("http://attendance2.localhost.com/api/branch_manage/edit/{$id}"));
        $branch = $response->json()['branch'];
        return view('admin.frontend.branch.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        Http::put(url("http://attendance2.localhost.com/api/branch_manage/update/{$id}"), $request->all());
        return redirect()->route('admin.frontend.branch.index');
    }

    public function destroy($id)
    {
        Http::delete(url("http://attendance2.localhost.com/api/branch_manage/delete/{$id}"));
        return redirect()->route('admin.frontend.branch.index');
    }
 
}
