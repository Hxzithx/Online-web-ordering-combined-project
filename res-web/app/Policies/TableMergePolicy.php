<?php

namespace App\Policies;

class TableMergePolicy
{
    public function before($user = null, $ability = null)
    {
        return true;
    }

    public function viewAny($user = null): bool { return true; }
    public function view($user = null, $model = null): bool { return true; }
    public function create($user = null): bool { return true; }
    public function update($user = null, $model = null): bool { return true; }
    public function delete($user = null, $model = null): bool { return true; }
}