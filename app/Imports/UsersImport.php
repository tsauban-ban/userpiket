<?php




namespace App\Imports;

use App\Models\User;
use App\Models\Division;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation, WithChunkReading
{
    use Importable;

    private $errors = [];
    private $successCount = 0;
    private $rowNumber = 1;

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->rowNumber++;
            
            
            

            if (empty($row['nama'])) {
                $this->errors[] = "Baris {$this->rowNumber}: Nama wajib diisi";
                continue;
            }
            
            if (empty($row['email'])) {
                $this->errors[] = "Baris {$this->rowNumber}: Email wajib diisi";
                continue;
            }
            
            
            

            if (User::where('email', $row['email'])->exists()) {
                $this->errors[] = "Baris {$this->rowNumber}: Email '{$row['email']}' sudah terdaftar";
                continue;
            }

            
            

            $division = Division::where('division_name', $row['divisi'])->first();
            
            if (!$division) {
                $this->errors[] = "Baris {$this->rowNumber}: Divisi '{$row['divisi']}' tidak ditemukan. Divisi yang tersedia: Macro, Management Account, IT Support, HRD";
                continue;
            }

            
            User::create([
                'name' => $row['nama'],
                'email' => $row['email'],
                'division_id' => $division->id,
                'password' => Hash::make($row['password'] ?? 'password123'),
                'role' => 'user',
            ]);
            
            $this->successCount++;
        }
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'divisi' => 'required|string',
            'password' => 'nullable|string|min:6'
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'divisi.required' => 'Divisi wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }
}