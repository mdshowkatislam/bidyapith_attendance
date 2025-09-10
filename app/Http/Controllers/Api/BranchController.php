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
            \Log::error('Branch creation failed: ' . $e->getMessage());

            return $this->errorResponse(
                'Failed to create branch',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update($uid, Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'branch_code' => [
                'required',
                'integer',
                Rule::unique('branches')->ignore($uid, 'uid')
            ],
            'branch_name_en' => [
                'required',
                'string',
                Rule::unique('branches')->ignore($uid, 'uid')
            ],
           
        ]);
        

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $branch = $this->branchService->updateByUid($uid, $request->all());

            if (!$branch) {
                return $this->errorResponse(
                    'Branch not found',
                    Response::HTTP_NOT_FOUND
                );
            }

            return $this->successResponseWithData(
                $branch,
                'Branch Updated Successfully',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            \Log::error('Branch update failed: ' . $e->getMessage());

            return $this->errorResponse(
                'Failed to update branch',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
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
