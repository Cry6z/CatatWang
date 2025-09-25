<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of users (Admin only).
     */
    public function index()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $users = User::orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user (Admin only).
     */
    public function create()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('admin.users.create');
    }

    /**
     * Store a newly created user (Admin only).
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,bendahara,anggota',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Show the form for editing a user (Admin only).
     */
    public function edit(User $user)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user (Admin only).
     */
    public function update(Request $request, User $user)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,bendahara,anggota',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove the specified user (Admin only).
     */
    public function destroy(User $user)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        // Prevent deleting the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus admin terakhir!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Show user statistics (Admin only).
     */
    public function statistics()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $stats = [
            'total_users' => User::count(),
            'admin_count' => User::where('role', 'admin')->count(),
            'bendahara_count' => User::where('role', 'bendahara')->count(),
            'anggota_count' => User::where('role', 'anggota')->count(),
            'recent_users' => User::latest()->take(5)->get(),
        ];

        return view('admin.users.statistics', compact('stats'));
    }
}
