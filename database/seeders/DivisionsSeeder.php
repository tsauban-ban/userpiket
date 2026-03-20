<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            // Data divisi - hanya 2
        $divisions = [
            [
                'division_name' => 'Macro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'division_name' => 'Management Account',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'division_name' => 'Programmer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data baru - PAKAI KURUNG KURAWAL {}
        foreach ($divisions as $division) {
            Division::create($division);
            $this->command->info('Divisi "' . $division['division_name'] . '" berhasil ditambahkan!');
        }}
}
