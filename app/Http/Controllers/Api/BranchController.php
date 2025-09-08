<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
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
            'branch_name_en' => 'required|string',
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
            $branch = $this->branchService->create($validator->validated());

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
            'branch_name_en' => 'required|string',
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
            $branch = $this->branchService->updateByUid($uid, $validator->validated());

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

    public function getById($uid)
    {
        try {
            $branch = $this->branchService->getById($uid);
            if ($branch) {
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
