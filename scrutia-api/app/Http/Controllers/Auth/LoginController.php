<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        if ($validated) {
            $user = User::where('username', $request->username)->first();

            if (!$user) {
                return response()->json([
                    'title' => 'error',
                    'description' => 'User not found'
                ], 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'title' => 'error',
                    'description' => 'Wrong credentials'
                ], 401);
            }

            return response()->json([
                'token' => $user->createToken($request->header('User-Agent'))->plainTextToken,
                'user' => $user
            ]);
        }
        return response()->json([
            'title' => 'error',
            'description' => 'Bad Request'
        ],400);
    }

    /**
     * Handle an incoming logout request.
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        auth()->user()->tokens()->where('tokenable_id', auth()->id() )->delete();
        return response()->json(['title' => 'Successfully logged out']);
    }
}
