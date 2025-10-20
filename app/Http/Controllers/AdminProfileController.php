<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile page
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Update the admin profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'email']);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store new image
            $file = $request->file('profile_image');
            $filename = time() . '_' . $user->id . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('profile_images', $filename, 'public');
            $data['profile_image'] = $path;
        }

        // Handle password change
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.profile')
            ->with('success', 'Profile updated successfully!');
    }
}