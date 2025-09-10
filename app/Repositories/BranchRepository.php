<?php

namespace App\Repositories;

use App\Models\Branch;
use App\Models\ShiftSetting;
use App\Repositories\Interfaces\BranchInterface;

class BranchRepository implements BranchInterface
{
    protected $model;

    public function __construct(Branch $branch)
    {
        $this->model = $branch;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getByUid($uid)
    {
        return $this->model->where('uid', $uid)->first();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($uid, $data)
    {
        $branch = $this->model->where('uid', $uid)->firstOrFail();
        $branch->fill($data);
        $branch->save();
        return $branch;
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
        if ($optimize) {
            return Branch::on('db_read')
                ->whereIn('uid', $branch_id)
                ->select('uid', 'branch_name')
                ->where('eiin', $eiin)
                ->get();
        } else {
            return Branch::on('db_read')
                ->whereIn('uid', $branch_id)
                ->select('uid', 'branch_name', 'branch_name_en', 'branch_location', 'head_of_branch_id', 'eiin', 'rec_status')
                ->where('eiin', $eiin)
                ->get();
            // return Branch::on('db_read')->where('eiin', $eiin)->get();
        }
    }

    public function delete($id)
    {
        return Branch::where('uid', $id)->delete();
    }

    public function getRelatedItemsForBranch($related_items, $id)
    {
        // $eiin = app('sso-auth')->user()->eiin;

        $related_items['shifts'] = ShiftSetting::where('branch_id', $id)->get();

        return $related_items;
    }

    // public function getByEiinIdWithPagination($eiin)
    // {
    //     return Branch::on('db_read')->where('eiin', $eiin)->paginate(20);
    // }
}
