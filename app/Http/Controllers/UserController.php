<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;

class UserController extends Controller
{
    /**
     * ✅ Display all users
     */
    public function index()
    {
        $users = User::with('group')->get(); // eager-load relationship
        return view('users.index', compact('users'));
    }

    /**
     * ✅ Show the Add New User form
     */
    public function create()
{
    $groups = Group::orderBy('group_level')->get();
    return view('users.create', compact('groups'));
}


    /**
     * ✅ Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'user_level' => 'required|integer',
        ]);

        // Create user
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'user_level' => $request->user_level,
            'status' => 1, // Default: Active
        ]);

        return redirect()
            ->route('users.create')
            ->with('success', '✅ User account has been created successfully!');
    }

    /**
     * ✅ Show the Edit User form
     */
    public function edit(User $user)
    {
        $groups = Group::orderBy('group_level')->get();
        return view('users.edit', compact('user', 'groups'));
    }

    /**
     * ✅ Update user account info
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'user_level' => 'required|integer',
            'status' => 'required|in:0,1',
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'user_level' => $request->user_level,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('users.edit', $user->id)
            ->with('success', '✅ Account updated successfully!');
    }

    /**
     * ✅ Change user password
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()
            ->route('users.edit', $user->id)
            ->with('success', '🔒 Password updated successfully!');
    }

    /**
     * ✅ Delete user account
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', '🗑️ User deleted successfully!');
    }
}
