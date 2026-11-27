<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->getData();
    }

    /** Data untuk DataTables di halaman admin (route: list-users). */
    public function getData(): JsonResponse
    {
        try {
            $users = User::orderBy('id')->get();

            return response()->json([
                'success' => true,
                'message' => 'Data user berhasil diambil.',
                'data' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'role' => ['nullable', Rule::in(array_keys(User::ROLES))],
        ]);

        // Hanya admin yang boleh menentukan role. Selain itu otomatis editor.
        if (! $request->user()->isAdmin() || empty($validated['role'])) {
            $validated['role'] = User::ROLE_EDITOR;
        }

        $validated['username'] = $this->makeUsername($validated['email']);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat.',
            'data' => $user,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return $this->notFound();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil diambil.',
            'data' => $user,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return $this->notFound();
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'role' => ['sometimes', Rule::in(array_keys(User::ROLES))],
            'is_active' => 'sometimes|boolean',
        ]);

        // Password kosong = tidak diganti
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Role / status hanya boleh diubah admin, dan bukan untuk akunnya sendiri
        if (! $request->user()->isAdmin() || $user->id === $request->user()->id) {
            unset($validated['role'], $validated['is_active']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui.',
            'data' => $user->fresh(),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return $this->notFound();
        }

        if ($user->id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa menghapus akun Anda sendiri.',
            ], 403);
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.',
        ]);
    }

    private function notFound(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Data user tidak ditemukan.',
        ], 404);
    }

    private function makeUsername(string $email): string
    {
        $base = Str::lower(Str::before($email, '@'));
        $username = $base;
        $i = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }

        return $username;
    }
}