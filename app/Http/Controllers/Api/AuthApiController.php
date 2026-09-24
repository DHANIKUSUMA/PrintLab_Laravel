<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    /**
     * Login User API
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi input gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi tidak valid'
            ], 401);
        }

        if (isset($user->status) && in_array($user->status, ['nonaktif', 'inactive'])) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sedang dinonaktifkan oleh administrator'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id_user' => $user->id_user,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status ?? 'aktif',
                ]
            ]
        ], 200);
    }

    /**
     * Mengambil daftar user (Admin)
     */
    public function users()
    {
        $users = User::select('id_user', 'name', 'email', 'role', 'status', 'created_at')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar user berhasil diambil',
            'data' => $users
        ], 200);
    }
}
