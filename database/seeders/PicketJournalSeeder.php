<?php
// database/seeders/PicketJournalSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PicketJournal;
use App\Models\User;
use Carbon\Carbon;

class PicketJournalSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        if ($users->isEmpty()) {
            $this->command->info('Tidak ada user dengan role user. Buat dulu!');
            return;
        }

        $statuses = ['Done','Pending', 'Sakit', 'Izin', 'Alpha', 'Terlambat'];
        
        foreach ($users as $user) {
            // Buat 5 data dummy per user
            for ($i = 0; $i < 5; $i++) {
                PicketJournal::create([
                    'user_id' => $user->id,
                    'date' => Carbon::now()->subDays(rand(1, 30)),
                    'activity' => 'Piket ' . ['Kebersihan', 'Keamanan', 'Kerapian'][rand(0, 2)],
                    'description' => 'Melaksanakan piket dengan baik',
                    'status' => $statuses[rand(0, 4)],
                    'notes' => rand(0, 1) ? 'Catatan: ' . rand(1, 100) : null
                ]);
            }
        }

        $this->command->info('Data jurnal piket berhasil ditambahkan!');
    }
}