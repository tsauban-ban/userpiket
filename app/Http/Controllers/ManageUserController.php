<?php




namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
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
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }
        
        $users = $query->orderBy('id', 'asc')->get();
        
        return view('admin.manageusers.index', compact('users', 'divisions'));
    }

    
    
    
    public function show($id)
    {
        
    
    
        return redirect()->route('manageusers.index');
    }

    public function store(Request $request)
    {
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

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'division_id' => $request->division_id,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect()->route('manageusers.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

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
            
            $errors = $import->getErrors();
            $successCount = $import->getSuccessCount();
            
            if (!empty($errors)) {
                $message = "Import selesai! Berhasil: {$successCount} user, Gagal: " . count($errors);
                return redirect()->back()
                    ->with('warning', $message)
                    ->with('import_errors', $errors);
            }
            
            return redirect()->back()
                ->with('success', "Import berhasil! {$successCount} user ditambahkan.");
                
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

    public function export(Request $request)
    {
        $query = User::with('division');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }
        
        $users = $query->orderBy('id', 'asc')->get();
        
        $fileName = 'data_users_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new UsersExport($users), $fileName);
    }
}