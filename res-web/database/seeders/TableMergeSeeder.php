<?php

namespace Database\Seeders;

use App\Models\TableMerge;
use App\Models\Table;
use Illuminate\Database\Seeder;

class TableMergeSeeder extends Seeder
{
    public function run(): void
    {
        // Make sure we have available tables first
        $tables = Table::where('status', 'available')->take(4)->get();

        if ($tables->count() < 2) {
            $this->command->info('Not enough available tables to create merges. Skipping.');
            return;
        }

        // Merge 1 - two tables
        TableMerge::create([
            'table_id_1' => $tables[0]->id,
            'table_id_2' => $tables[1]->id,
            'table_id_3' => null,
            'user_id'    => 1,
            'merged_at'  => now(),
        ]);

        // Update table status to reserved
        Table::whereIn('id', [$tables[0]->id, $tables[1]->id])
            ->update(['status' => 'reserved']);

        // Merge 2 - three tables (only if we have enough)
        if ($tables->count() >= 4) {
            TableMerge::create([
                'table_id_1' => $tables[2]->id,
                'table_id_2' => $tables[3]->id,
                'table_id_3' => null,
                'user_id'    => 1,
                'merged_at'  => now()->subHours(2),
            ]);

            Table::whereIn('id', [$tables[2]->id, $tables[3]->id])
                ->update(['status' => 'reserved']);
        }

        $this->command->info('Table merges seeded successfully.');
    }
}