<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama dulu (opsional)
        // User::truncate();
        // Division::truncate();
        
        // Buat Divisi
        $divisions = [
            ['division_name' => 'Macro'],
            ['division_name' => 'Management Account'],
        ];

        foreach ($divisions as $div) {
            Division::create($div);
        }

        // Buat Admin - PASTIKAN TIDAK ADA TITIK DI EMAIL
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',  // HARUS 'admin@gmail.com' bukan 'ad.min.@gm.ail.com'
            'password' => Hash::make('admin123'),  // Gunakan Hash::make()
            'role' => 'admin',  // HARUS 'admin' bukan 'ad.min'
            'division_id' => null
        ]);

        // Buat User biasa
        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',  // HARUS 'user@gmail.com'
            'password' => Hash::make('user123'),
            'role' => 'user',  // HARUS 'user' bukan 'u.ser'
            'division_id' => 1  // Macro division
        ]);

        // Buat user tambahan
        User::create([
            'name' => 'Kinandaru',
            'email' => 'kinan@gmail.com',
            'password' => Hash::make('kinan123'),
            'role' => 'user',
            'division_id' => 1  // Macro division
        ]);

        User::create([
            'name' => 'Budi',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('budi123'),
            'role' => 'user',
            'division_id' => 2  // Management Account division
        ]);

        // Panggil PicketJournalSeeder
        $this->call(PicketJournalSeeder::class);
    }
}