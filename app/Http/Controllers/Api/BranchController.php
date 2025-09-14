<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use App\Services\BranchService;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    use ApiResponse;

    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index()
    {
        $branches = Branch::where('rec_status', 1)->get();
        //   \Log::info( $branches);
        if (count($branches) > 0) {
            return response()->json([
                'message' => 'Branches fetched successfully.',
                'branches' => $branches,
            ], 200);
        }
        return response()->json([
            'message' => 'Branches not found.'
        ], 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_code' => 'required|integer|unique:branches,branch_code',
            'branch_name_en' => 'required|string| unique:branches,branch_name_en',
            'branch_location' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $branch = $this->branchService->create($request->all());

            return $this->successResponseWithData(
                $branch,
                'Branch Stored Successfully',
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            Log::error('Branch creation failed: ' . $e->getMessage());

            return $this->errorResponse(
                'Failed to create branch',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update($uid, Request $request)
    {
        
        $branch = Branch::where('uid', $uid)->first();
    
        if (!$branch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Branch not found'
            ], 404);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'branch_code' => [
                'required',
                'integer',
                Rule::unique('branches', 'branch_code')->ignore($uid, 'uid'),
            ],
            'branch_name_en' => [
                'nullable',
                'string',
                Rule::unique('branches', 'branch_name_en')->ignore($uid, 'uid'),
            ],
            'branch_name_bn' => ['nullable', 'string'],
            'branch_location' => ['nullable', 'string'],
            'head_of_branch_id' => ['nullable', 'integer'],
            'eiin' => ['nullable', 'integer'],
            'rec_status' => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Cast numeric fields properly
            $data = $request->all();
            $data['branch_code'] = isset($data['branch_code']) ? (int) $data['branch_code'] : null;
            $data['head_of_branch_id'] = isset($data['head_of_branch_id']) ? (int) $data['head_of_branch_id'] : null;
            $data['eiin'] = isset($data['eiin']) ? (int) $data['eiin'] : null;
            $data['rec_status'] = isset($data['rec_status']) ? (int) $data['rec_status'] : 1;

            // Update branch
            $branch->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Branch updated successfully',
                'data' => $branch
            ], 200);
        } catch (\Exception $e) {
            Log::error('Branch update failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update branch'
            ], 500);
        }
    }

    public function getByUid($uid)
    {
        try {
            $branch = $this->branchService->getByUid($uid);
            if ($branch) {
                return $this->successResponse($branch, Response::HTTP_OK);
            } else {
                return $this->errorResponse('Sorry , No Data found !', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Sorry , No Data found !', Response::HTTP_NOT_FOUND);
        }
    }

    public function edit($uid)
    {
        // \Log::info('Edit method called with UID: ' . $uid);
        try {
            $branch = $this->branchService->getByUid($uid);
            //   \Log::info(  $branch );exit();

            if ($branch) {
                // $branchHeadTeacher=Employee::where('is_teacher',1)
                // ->where('is_branchHead',1)->where('rec_status',1)
                // ->select('id','name')->get(); // this could be done using relation in future or from teacher table.
                //   $data=['branch'=>$branch,'branchHeadTeacher'=>$branchHeadTeacher];
                // return $this->successResponse($data, Response::HTTP_OK);
                return $this->successResponse($branch, Response::HTTP_OK);
            } else {
                return $this->errorResponse('Sorry , No Data found !', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Sorry , No Data found !', Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($uid)
    {
        try {
            $result = $this->branchService->deleteByUid($uid);

            if ($result) {
                return $this->successResponse(
                    'Branch Deleted Successfully',
                    Response::HTTP_OK
                );
            }

            return $this->errorResponse(
                'Branch not found',
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            \Log::error('Branch deletion failed: ' . $e->getMessage());

            return $this->errorResponse(
                'Failed to delete branch',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
