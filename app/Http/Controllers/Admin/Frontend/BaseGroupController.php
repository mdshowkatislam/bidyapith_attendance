<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ShiftSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BaseGroupController extends Controller
{
    public function index()
    {;
        try {
           
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ])
            
                ->withOptions(['verify' => false])
                ->get('http://attendance2.localhost.com/api/group_manage/list');

                // dd($response->json());
            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === true) {
                    $groups = $data['data'] ?? [];
                    return view('admin.frontend.group.index', compact('groups'));
                } else {
                    return redirect()
                        ->route('group_manage.index')
                        ->with('error', $data['message'] ?? 'Failed to fetch groups');
                }
            } else {
                return redirect()
                    ->route('group_manage.index')
                    ->with('error', 'API request failed: ' . $response->status());
            }
        } catch (\Exception $e) {
            return redirect()
                ->route('group_manage.index')
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function add()
    { 
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ])
                ->withOptions(['verify' => false])
                ->get('http://attendance2.localhost.com/api/group_manage/add');
       
            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === true) {
                    $employees = $data['data']['employees'] ?? [];
                    $workDays = $data['data']['workDays'] ?? [];
                    $shifts = $data['data']['shifts'] ?? [];
                    $branches = $data['data']['branches'] ?? [];

                    // Check if required data is available
                    if (empty($employees) || empty($workDays) || empty($branches)) {
                        return redirect()
                            ->route('error-page')
                            ->with('error', 'Required data missing for creating group');
                    }

                    return view('admin.frontend.group.create', compact('workDays', 'employees', 'shifts', 'branches'));
                } else {
                    return redirect()
                        ->route('group_manage.index')
                        ->with('error', $data['message'] ?? 'Failed to fetch group creation data');
                }
            } else {
                return redirect()
                    ->route('group_manage.index')
                    ->with('error', 'API request failed: ' . $response->status());
            }
        } catch (\Exception $e) {
            return redirect()
                ->route('group_manage.index')
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function getGroupData($id)
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->get("http://attendance2.localhost.com/api/group_manage/details/{$id}");
 
            // dd($response->json());
            if ($response->successful()) {
                $data = $response->json();
                return $data['status'] === true ? $data['data'] : null;
            }

            return null;
        } catch (\Exception $e) {
            return $this->errorResponse($e, 'Error fetching group data');
        }
    }

    public function previewPdfView($id)
    {
        $group = $this->getGroupData($id);
                // dd($group['employees'] ); 

        if (!$group) {
            return redirect()
                ->route('group_manage.index')
                ->with('error', 'Group not found or failed to fetch group details');
        }



        return view('admin.frontend.group.pdf', compact('group'));
    }

    public function downloadGroupPdf($id)
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->get("http://attendance2.localhost.com/api/group_manage/download-pdf/{$id}");
            // dd($response->body());
            if ($response->successful()) {
                $contentDisposition = $response->header('Content-Disposition');
                // dd($contentDisposition);    
                $filename = 'group-details.pdf';
                if (preg_match('/filename="([^"]+)"/', $contentDisposition, $matches)) {
                    $filename = $matches[1];
                  
                }
 
                // Return the PDF as a download
                return response()->streamDownload(function () use ($response) {
                    echo $response->body();
                }, $filename, [
                    'Content-Type' => 'application/pdf',
                ]);
            } else {
                          
                return redirect()
                    ->route('group_manage.index')
                    ->with('error', 'Failed to generate PDF: ' . $response->status());
            }
        } catch (\Exception $e) {
            return redirect()
                ->route('group_manage.index')
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->post('http://attendance2.localhost.com/api/group_manage/store', $request->all());

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? false) === true) {
                return response()->json([
                    'status' => true,
                    'message' => $data['message'] ?? 'Group created successfully.'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $data['message'] ?? 'Group creation failed.',
                    'errors' => $data['errors'] ?? null
                ], $response->status() ?? 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->get("http://attendance2.localhost.com/api/group_manage/edit/{$id}");

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === true) {
                    $group = $data['data']['group'] ?? null;
                    $employees = $data['data']['employees'] ?? [];
                    $workDays = $data['data']['workDays'] ?? [];
                    $shifts = $data['data']['shifts'] ?? [];
                    $branches = $data['data']['branches'] ?? [];

                    if (!$group) {
                        return redirect()
                            ->route('group_manage.index')
                            ->with('error', 'Group not found');
                    }

                    return view('admin.frontend.group.edit', compact('group', 'shifts', 'employees', 'workDays', 'branches'));
                } else {
                    return redirect()
                        ->route('group_manage.index')
                        ->with('error', $data['message'] ?? 'Failed to fetch group data');
                }
            } else {
                return redirect()
                    ->route('group_manage.index')
                    ->with('error', 'API request failed: ' . $response->status());
            }
        } catch (\Exception $e) {
            return redirect()
                ->route('group_manage.index')
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->post("http://attendance2.localhost.com/api/group_manage/update/{$id}", $request->all());

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? false) === true) {
                return response()->json([
                    'status' => true,
                    'message' => $data['message'] ?? 'Group updated successfully.'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $data['message'] ?? 'Group update failed.',
                    'errors' => $data['errors'] ?? null
                ], $response->status() ?? 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->delete("http://attendance2.localhost.com/api/group_manage/delete/{$id}");

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? false) === true) {
                return redirect()
                    ->route('group_manage.index')
                    ->with('success', $data['message'] ?? 'Group deleted successfully');
            } else {
                return redirect()
                    ->route('group_manage.index')
                    ->with('error', $data['message'] ?? 'Failed to delete group');
            }
        } catch (\Exception $e) {
            Log::error('API call failed: ' . $e->getMessage());
            return redirect()
                ->route('group_manage.index')
                ->with('error', 'Something went wrong while deleting: ' . $e->getMessage());
        }
    }
}
