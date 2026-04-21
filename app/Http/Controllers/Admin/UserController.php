<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ProgramStudi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of all users
     */
    public function index()
    {
        $users = User::orderBy('nama', 'asc')->paginate(10);
        $programStudis = ProgramStudi::orderBy('nama', 'asc')->get();
        return view('admin.users.index', compact('users', 'programStudis'));
    }

    /**
     * Store a newly created user in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,dosen,validator',
        ], [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        try {
            User::create($validated);
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Update the specified user in database
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',user_id',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,dosen,validator',
        ], [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid',
        ]);

        // Hash password only if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        try {
            $user->update($validated);
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    /**
     * Delete the specified user from database
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    /**
     * Assign program studi to user
     */
    public function assignProdi(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'prodi_id' => 'required|exists:program_studi,prodi_id',
        ], [
            'prodi_id.required' => 'Program Studi harus dipilih',
            'prodi_id.exists' => 'Program Studi tidak ditemukan',
        ]);

        try {
            // Sync the program studi to the user (replace existing with new one)
            $user->prodis()->sync([$validated['prodi_id']]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Program Studi berhasil di-assign ke user');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal assign Program Studi: ' . $e->getMessage());
        }
    }
}
