<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function register(RegistrationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        if ($validated) {
            $user = User::create([
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'token' => $user->createToken($request->header('User-Agent'))->plainTextToken,
                'user' => $user
            ], 201);
        }
        return response()->json([
            'title' => 'Error: Bad Request',
            'description' => 'Error during registration'
        ], 401);
    }
}
