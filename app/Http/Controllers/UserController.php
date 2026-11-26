<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SecurityAuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan halaman manajemen pengguna dengan data riil dari database.
     */
    public function index()
    {
        try {
            $dbUsers = User::orderBy('id', 'asc')->get();
        } catch (\Throwable $e) {
            $dbUsers = collect([]);
        }

        $totalUsers = $dbUsers->count();
        $adminCount = $dbUsers->where('role', 'ADMINISTRATOR')->count();
        $operatorCount = $dbUsers->where('role', 'OPERATOR')->count();
        $activeCount = $dbUsers->where('status', 'AKTIF')->count();

        return view('manajemen-pengguna', compact('dbUsers', 'totalUsers', 'adminCount', 'operatorCount', 'activeCount'));
    }

    /**
     * Simpan pengguna baru (POST /users)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:ADMINISTRATOR,OPERATOR',
            'status'   => 'nullable|in:AKTIF,NONAKTIF',
            'phone'    => 'nullable|string|max:30',
        ], [
            'username.unique' => 'Username ini sudah terdaftar. Silakan gunakan username lain.',
            'password.min'    => 'Kata sandi minimal 6 karakter.',
        ]);

        // Generate User Code unik (USR-YRS-xxx)
        $latestId = User::max('id') ?? 0;
        $nextNum = str_pad($latestId + 1, 3, '0', STR_PAD_LEFT);
        $userCode = 'USR-YRS-' . $nextNum;

        $user = User::create([
            'user_code' => $userCode,
            'name'      => $validated['name'],
            'username'  => strtolower(trim(str_replace('@', '', $validated['username']))),
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'],
            'status'    => $validated['status'] ?? 'AKTIF',
            'phone'     => $validated['phone'] ?? null,
        ]);

        // Catat Audit Trail
        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'USER_CREATE',
                'severity'    => 'INFO',
                'description' => 'Penambahan pengguna baru: ' . $user->name . ' (' . $user->role . ') dengan kode ' . $user->user_code . '.',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'payload_json'=> ['user_id' => $user->id, 'role' => $user->role],
            ]);
        } catch (\Throwable $e) {}

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna ' . $user->name . ' berhasil ditambahkan.',
                'data'    => $user,
            ], 201);
        }

        return redirect()->route('manajemen-pengguna')->with('success', 'Pengguna ' . $user->name . ' berhasil ditambahkan.');
    }

    /**
     * Perbarui data pengguna (PUT/POST /users/{id})
     */
    public function update(Request $request, $id)
    {
        // Cari user by id atau user_code
        $user = User::where('id', $id)->orWhere('user_code', $id)->firstOrFail();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'role'     => 'required|in:ADMINISTRATOR,OPERATOR',
            'status'   => 'nullable|in:AKTIF,NONAKTIF',
            'phone'    => 'nullable|string|max:30',
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'name'     => $validated['name'],
            'username' => strtolower(trim(str_replace('@', '', $validated['username']))),
            'role'     => $validated['role'],
            'status'   => $validated['status'] ?? $user->status,
            'phone'    => $validated['phone'] ?? $user->phone,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Catat Audit Trail
        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'USER_UPDATE',
                'severity'    => 'INFO',
                'description' => 'Pembaruan data pengguna: ' . $user->name . ' (' . $user->user_code . ').',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pengguna ' . $user->name . ' berhasil diperbarui.',
                'data'    => $user,
            ]);
        }

        return redirect()->route('manajemen-pengguna')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Ubah status Aktif / Nonaktif (POST /users/{id}/toggle-status)
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::where('id', $id)->orWhere('user_code', $id)->firstOrFail();
        $newStatus = ($user->status === 'AKTIF') ? 'NONAKTIF' : 'AKTIF';

        $user->update(['status' => $newStatus]);

        // Catat Audit Trail
        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'USER_STATUS_CHANGE',
                'severity'    => 'WARNING',
                'description' => 'Perubahan status akun ' . $user->name . ' menjadi ' . $newStatus . '.',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success'   => true,
            'new_status'=> $newStatus,
            'message'   => 'Status pengguna ' . $user->name . ' berhasil diubah menjadi ' . $newStatus . '.',
        ]);
    }

    /**
     * Reset Password Pengguna (POST /users/{id}/reset-password)
     */
    public function resetPassword(Request $request, $id)
    {
        $user = User::where('id', $id)->orWhere('user_code', $id)->firstOrFail();

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'       => 'Kata sandi baru minimal 6 karakter.',
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Catat Audit Trail
        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'PASSWORD_RESET',
                'severity'    => 'WARNING',
                'description' => 'Reset kata sandi pengguna untuk: ' . $user->username . ' (' . $user->user_code . ').',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi untuk pengguna ' . $user->name . ' berhasil diatur ulang.',
        ]);
    }

    /**
     * Hapus Pengguna (DELETE /users/{id})
     */
    public function destroy(Request $request, $id)
    {
        $user = User::where('id', $id)->orWhere('user_code', $id)->firstOrFail();

        // Cegah menghapus akun yang sedang login
        if ($user->id === session('gearsense_user_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.',
            ], 403);
        }

        $userName = $user->name;
        $userCode = $user->user_code;
        $user->delete();

        // Catat Audit Trail
        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'USER_DELETE',
                'severity'    => 'WARNING',
                'description' => 'Penghapusan akun pengguna: ' . $userName . ' (' . $userCode . ').',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'message' => 'Pengguna ' . $userName . ' berhasil dihapus dari sistem.',
        ]);
    }
}
