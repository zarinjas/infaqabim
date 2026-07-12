<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::guard('admins')->attempt($credentials, $request->boolean('remember'))) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        $request->session()->regenerate();

        $admin = Auth::guard('admins')->user();

        return response()->json([
            'message' => 'Logged in successfully',
            'admin' => $admin,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('admins')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(): JsonResponse
    {
        $admin = Auth::guard('admins')->user();

        if (! $admin) {
            return response()->json(['message' => 'Not authenticated'], 401);
        }

        return response()->json(['admin' => $admin]);
    }
}
