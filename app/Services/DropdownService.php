<?php

namespace App\Services;

use App\Repositories\BranchRepository;

class BranchService
{
    private $branchRepository;

    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function getAll()
    {
        return $this->branchRepository->getAll();
    }

    public function getByUid($uid)
    {
        return $this->branchRepository->getByUid($uid);
    }

    public function create($data)
    {
      
        return $this->branchRepository->create($data);
    }

    public function updateByUid($uid, $data)
    {
        return $this->branchRepository->update($uid, $data);
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return $this->model->where('eiin', $eiin)->select('uid', 'branch_name')->first();
        } else {
            return $this->model->where('eiin', $eiin)->select('uid', 'branch_name', 'branch_name_en', 'branch_location', 'head_of_branch_id', 'eiin', 'rec_status')->first();
        }
    }

    public function getByBranchId($eiin, $optimize = null, $branch_id)
    {
        return $this->branchRepository->getByBranchId($eiin, $optimize = null, $branch_id);
    }

    public function deleteByUid($id)
    {
        return $this->branchRepository->delete($id);
    }

    public function getRelatedItemsForBranch($related_items, $id)
    {
        return $this->branchRepository->getRelatedItemsForBranch($related_items, $id);
    }
}
