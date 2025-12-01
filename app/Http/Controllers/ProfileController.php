<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * 🧍 Show the current user's profile page
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * ⚙️ Show edit profile page (for both photo + info)
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * 🖼️ Update only the user photo (separate form)
     */
    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $path = $request->file('image')->store('uploads/users', 'public');
        $user->image = $path;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile photo updated successfully!');
    }

    /**
     * ✏️ Update user info (name + username)
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Account information updated successfully!');
    }

    /**
     * 🆕 Combined update method for both info and photo
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 🖼️ Handle profile image update
if ($request->hasFile('image')) {

    // Delete old image
    if ($user->image && Storage::disk('public')->exists($user->image)) {
        Storage::disk('public')->delete($user->image);
    }

    // Store new image in storage/app/public/uploads/users/
    $path = $request->file('image')->store('uploads/users', 'public');

    $user->image = $path; // Example: uploads/users/12345.jpg
}


return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');

    }

    /**
     * 🔒 Show change password form
     */
    public function editPassword()
    {
        $user = Auth::user();
        return view('profile.change_password', compact('user'));
    }

    /**
     * 🔑 Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Password changed successfully!');
    }
}
