<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::with('school')
            ->whereIn('user_role', ['admin', 'facilitator', 'teacher'])
            ->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $schools = School::orderBy('school_name')->get();
        return view('admin.users.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'user_role' => 'required|in:admin,facilitator,teacher',
            'school_id' => 'nullable|exists:schools,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Prevent creating admin accounts - only super admins can create admins
        if ($validated['user_role'] === 'admin' && auth()->user()->user_role !== 'super_admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'You do not have permission to create administrator accounts.');
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $schools = School::orderBy('school_name')->get();
        return view('admin.users.edit', compact('user', 'schools'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'user_role' => 'required|in:admin,facilitator,teacher',
            'school_id' => 'nullable|exists:schools,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Prevent changing admin roles - only super admins can modify admin accounts
        if ($user->user_role === 'admin' && auth()->user()->user_role !== 'super_admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'You do not have permission to modify administrator accounts.');
        }

        // Prevent promoting to admin - only super admins can create admins
        if ($validated['user_role'] === 'admin' && auth()->user()->user_role !== 'super_admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'You do not have permission to create administrator accounts.');
        }

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting admin accounts
        if ($user->user_role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Administrator accounts cannot be deleted.');
        }

        // Prevent deleting your own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}
