<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PicketJournal;
use App\Models\User;

class PicketJournalController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();

        $journals = PicketJournal::with('user')
            ->when($request->search, function ($query) use ($request) {
                $query->where('activity', 'like', '%' . $request->search . '%');
            })
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('date', $request->date);
            })
            ->latest()
            ->get();

        return view('admin.picketjournal.index', compact('journals','users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'date' => 'required|date',
            'activity' => 'required',
            'status' => 'required'
        ]);

        PicketJournal::create($request->all());

        return redirect()->route('admin.picketjournal.index')
            ->with('success','Journal added successfully');
    }


    public function update(Request $request, $id)
    {
        $journal = PicketJournal::findOrFail($id);

        $journal->update($request->all());

        return redirect()->route('admin.picketjournal.index')
            ->with('success','Journal updated successfully');
    }


    public function destroy($id)
    {
        $journal = PicketJournal::findOrFail($id);
        $journal->delete();

        return redirect()->route('admin.picketjournal.index')
            ->with('success','Journal deleted successfully');
    }
}