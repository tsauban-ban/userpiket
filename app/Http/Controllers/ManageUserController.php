<?php

namespace App\Http\Controllers;
 
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersTemplateExport;
use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManageUserController extends Controller
{
    public function index(Request $request)
    {
         
        $divisions = Division::all();
        
        $query = User::with('division');
        
        // Filter berdasarkan pencarian (search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan divisi (division)
        if ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }
        
         
        $users = $query->orderBy('id', 'asc')->get();
        
        // Kirim data ke view
        return view('admin.manageusers.index', compact('users', 'divisions'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'division_id' => 'required|exists:divisions,id',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa kembali form Anda.');
        }

        // Buat user baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'division_id' => $request->division_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('manageusers.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'division_id' => 'required|exists:divisions,id',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa kembali form Anda.');
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'division_id' => $request->division_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('manageusers.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('manageusers.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new UsersImport();
            Excel::import($import, $request->file('file'));
            
            $errors = method_exists($import, 'getErrors') ? $import->getErrors() : [];
            
            if (!empty($errors)) {
                return redirect()->back()
                    ->with('warning', 'Import selesai dengan beberapa kesalahan')
                    ->with('import_errors', $errors);
            }
            
            return redirect()->back()
                ->with('success', 'Data users berhasil diimport!');
                
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            
            foreach ($failures as $failure) {
                $errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            return redirect()->back()
                ->with('error', 'Gagal mengimport data')
                ->with('import_errors', $errors);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        // Data contoh untuk template
        $data = [
            ['nama' => 'John Doe', 'email' => 'john@example.com', 'divisi' => 'IT', 'password' => 'password123'],
            ['nama' => 'Jane Smith', 'email' => 'jane@example.com', 'divisi' => 'HR', 'password' => 'password123'],
            ['nama' => 'Bob Johnson', 'email' => 'bob@example.com', 'divisi' => 'Finance', 'password' => 'password123'],
        ];

        // Export ke Excel
        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return [
                    'nama',
                    'email',
                    'divisi',
                    'password'
                ];
            }
        }, 'template_import_users.xlsx');
    }
}