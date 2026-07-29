<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\TableMerge;
use Illuminate\Auth\Access\HandlesAuthorization;

class TablePolicy
{
    use HandlesAuthorization;

    /**
     * Customer can view all table merges
     */
    public function viewAny(Customer $customer): bool
    {
        return true;
    }

    /**
     * Customer can view single table merge
     */
    public function view(Customer $customer, TableMerge $tableMerge): bool
    {
        return true;
    }

    /**
     * Customer can create table merge
     * (needed if customer reserves merged tables)
     */
    public function create(Customer $customer): bool
    {
        return true;
    }

    /**
     * Customer can update own merge request only
     */
    public function update(Customer $customer, TableMerge $tableMerge): bool
    {
        return true;
    }

    /**
     * Customer cannot delete merge directly
     */
    public function delete(Customer $customer, TableMerge $tableMerge): bool
    {
        return false;
    }
}