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
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(["message" => "Not Found", "errors" => [
                "User not found"
            ]], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(["message" => "Unauthorized", "errors" => [
                "Wrong username or password"
            ]], 401);
        }

        $user["nb_favorites"] = $user->favorites()->count();
        $user["nb_projects"] = $user->projects()->count();

        return response()->json([
            'token' => $user->createToken($request->header('User-Agent'))->plainTextToken,
            'user' => $user,
        ]);
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
