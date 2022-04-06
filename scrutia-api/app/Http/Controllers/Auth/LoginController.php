<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

            if (!$user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'error' => 'Wrong credentials'
                ], 401);
            }
        }

        return response()->json([
            'token' => $user->createToken($request->header('User-Agent'))->plainTextToken,
            'user' => $user
        ]);
    }

    /**
     * Handle an incoming logout request.
     *
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        return auth()->user()->tokens()->where('tokenable_id', auth()->id() )->delete();
    }
}
