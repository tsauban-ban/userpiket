<?php
// app/Http/Controllers/DivisionController.php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::withCount('users')->with('users')->get();
        return view('admin.division.index', compact('divisions'));
    }

    public function show(Division $division)
    {
        $division->load('users');
        return view('admin.division.detail-users', compact('division'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'division_name' => 'required|string|max:255|unique:divisions,division_name'
        ]);

        Division::create([
            'division_name' => $request->division_name,
            'total_users' => 0
        ]);

        return redirect()->route('divisions.index')
            ->with('success', 'Divisi berhasil ditambahkan!');
    }

    public function update(Request $request, Division $division)
    {
        $request->validate([
            'division_name' => 'required|string|max:255|unique:divisions,division_name,' . $division->id
        ]);

        $division->update([
            'division_name' => $request->division_name
        ]);

        return redirect()->route('divisions.index')
            ->with('success', 'Divisi berhasil diupdate!');
    }

    public function destroy(Division $division)
    {
        if ($division->users()->count() > 0) {
            return redirect()->route('divisions.index')
                ->with('error', 'Tidak bisa menghapus divisi yang masih memiliki user!');
        }

        $division->delete();

        return redirect()->route('divisions.index')
            ->with('success', 'Divisi berhasil dihapus!');
    }
}