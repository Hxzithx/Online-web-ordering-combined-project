<?php

namespace App\Policies;

use App\Models\TableMerge;
use App\Models\Customer;

class TableMergePolicy
{
    /**
     * Customer can view all table merges
     */
    public function viewAny(Customer $customer): bool
    {
        return true;
    }

    /**
     * Customer can view a single table merge
     */
    public function view(Customer $customer, TableMerge $tableMerge): bool
    {
        return true;
    }

    /**
     * Customer cannot create table merges
     */
    public function create(Customer $customer): bool
    {
        return false;
    }

    /**
     * Customer cannot update table merges
     */
    public function update(Customer $customer, TableMerge $tableMerge): bool
    {
        return false;
    }

    /**
     * Customer cannot delete table merges
     */
    public function delete(Customer $customer, TableMerge $tableMerge): bool
    {
        return false;
    }
}