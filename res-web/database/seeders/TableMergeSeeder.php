<?php

namespace Database\Seeders;

use App\Models\TableMerge;
use App\Models\Table;
use Illuminate\Database\Seeder;

class TableMergeSeeder extends Seeder
{
    public function run(): void
    {
        // Get all table IDs currently involved in active merges
        $activeMergedTableIds = TableMerge::pluck('table_id_1')
            ->merge(TableMerge::pluck('table_id_2'))
            ->merge(TableMerge::pluck('table_id_3'))
            ->filter()
            ->unique()
            ->toArray();

        if (empty($activeMergedTableIds)) {
            // If no active table merges exist, roll back all reserved tables to available
            Table::where('status', 'reserved')->update(['status' => 'available']);
            $this->command->info('No active table merges found. Rolled back all table statuses to available.');
        } else {
            // Reset tables not in active merges back to available
            Table::whereNotIn('id', $activeMergedTableIds)->update(['status' => 'available']);
            Table::whereIn('id', $activeMergedTableIds)->update(['status' => 'reserved']);
            $this->command->info('Table merge statuses synchronized successfully.');
        }
    }
}