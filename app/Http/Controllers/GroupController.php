<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    /**
     * Display a listing of all active groups.
     */
    public function index()
    {
        // Show only valid active groups
        $groups = Group::whereNotNull('group_name')
            ->whereNotNull('group_level')
            ->where('group_status', 1)
            ->orderBy('group_level', 'asc')
            ->get();

        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new group.
     */
    public function create()
    {
        // Get next group level number
        $maxLevel = Group::max('group_level') ?? 0;
        $nextLevel = $maxLevel + 1;

        return view('groups.create', compact('nextLevel'));
    }

    /**
     * Store a newly created group in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255|unique:user_groups,group_name',
            'group_level' => 'required|integer|unique:user_groups,group_level',
            'group_status' => 'required|in:0,1',
        ]);

        Group::create([
            'group_name' => $request->group_name,
            'group_level' => $request->group_level,
            'group_status' => $request->group_status,
        ]);

        return redirect()
            ->route('groups.index')
            ->with('success', 'Group has been created successfully!');
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit($id)
    {
        $group = Group::find($id);

        if (!$group) {
            return redirect()
                ->route('groups.index')
                ->with('error', 'Missing Group ID.');
        }

        return view('groups.edit', compact('group'));
    }

    /**
     * Update the specified group in storage.
     */
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $request->validate([
            'group_name' => 'required|string|max:255|unique:user_groups,group_name,' . $group->id,
            'group_level' => 'required|integer|unique:user_groups,group_level,' . $group->id,
            'group_status' => 'required|in:0,1',
        ]);

        $group->update([
            'group_name' => $request->group_name,
            'group_level' => $request->group_level,
            'group_status' => $request->group_status,
        ]);

        return redirect()
            ->route('groups.edit', $group->id)
            ->with('success', 'Group has been updated successfully!');
    }

    /**
     * Remove the specified group from storage.
     */
    public function destroy($id)
    {
        $group = Group::find($id);

        if (!$group) {
            return redirect()
                ->route('groups.index')
                ->with('error', 'Group not found.');
        }

        $group->delete();

        return redirect()
            ->route('groups.index')
            ->with('success', 'Group has been deleted successfully!');
    }
}
