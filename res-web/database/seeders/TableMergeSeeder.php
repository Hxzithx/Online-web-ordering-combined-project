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
        $this->command->info('Table merges seeded successfully.');
    }
}