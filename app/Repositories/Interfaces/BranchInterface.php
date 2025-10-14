<?php

namespace App\Repositories\Interfaces;

interface BranchInterface
{
    public function getAll();
    public function getByUid($id);
     public function getByEiinId($eiin);
    public function create($data);
}
