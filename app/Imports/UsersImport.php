<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Division;
use App\Models\Divisions;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    private $errors = [];

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Cari division_id berdasarkan nama divisi
        $division = Divisions::where('division_name', $row['divisi'])->first();
        
        if (!$division) {
            $this->errors[] = "Divisi '{$row['divisi']}' tidak ditemukan untuk user {$row['nama']}";
            return null;
        }

        // Cek apakah email sudah ada
        if (User::where('email', $row['email'])->exists()) {
            $this->errors[] = "Email '{$row['email']}' sudah terdaftar untuk user {$row['nama']}";
            return null;
        }

        return new User([
            'name'     => $row['nama'],
            'email'    => $row['email'],
            'division_id' => $division->id,
            'password' => Hash::make($row['password'] ?? 'password123'),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'divisi' => 'required|string',
            'password' => 'nullable|string|min:6'
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }
}