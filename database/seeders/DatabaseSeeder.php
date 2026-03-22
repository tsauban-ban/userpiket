<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;
use App\Notifications\BroadcastNotification; // TAMBAHKAN INI

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

        // Buat Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'division_id' => null
        ]);

        // Buat User biasa
        $user = User::create([  // UBAH: simpan ke variabel $user
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'division_id' => 1  // Macro division
        ]);

        // Buat user tambahan
        $kinan = User::create([  // UBAH: simpan ke variabel $kinan
            'name' => 'Kinandaru',
            'email' => 'kinan@gmail.com',
            'password' => Hash::make('kinan123'),
            'role' => 'user',
            'division_id' => 1  // Macro division
        ]);

        $budi = User::create([  // UBAH: simpan ke variabel $budi
            'name' => 'Budi',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('budi123'),
            'role' => 'user',
            'division_id' => 2  // Management Account division
        ]);

        // ========== TAMBAHKAN NOTIFIKASI DI SINI ==========
        // Notifikasi untuk User (user@gmail.com)
        $user->notify(new BroadcastNotification(
            'Hari ini Jadwal Piket untuk Budi dan Kinan, di Senin, 30 Maret 2026',
            'info',
            'Admin'
        ));
        
        $user->notify(new BroadcastNotification(
            'Piket Kebersihan telah selesai',
            'success',
            'Admin'
        ));
        
        $user->notify(new BroadcastNotification(
            'Jangan lupa piket keamanan hari ini',
            'warning',
            'Admin'
        ));
        
        // Notifikasi untuk Kinandaru 
        $kinan->notify(new BroadcastNotification(
            'Selamat datang di sistem piket',
            'info',
            'Admin'
        ));
        
        // Notifikasi untuk Budi
        $budi->notify(new BroadcastNotification(
            'Jadwal piket Anda minggu ini, Senin, 30 Maret 2026',
            'info',
            'Admin'
        ));
        // ==================================================

        // Panggil PicketJournalSeeder
        $this->call(PicketJournalSeeder::class);
    }
}