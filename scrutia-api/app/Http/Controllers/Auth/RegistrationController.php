<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(RegistrationRequest $request): JsonResponse
    {
        $unique = User::where('username', $request->username)->orWhere('email', $request->email)->first();

        if($unique != null){
            return response()->json(["message" => "Bad Request", "errors" => [
                "Username or email already exists"
            ]], 400);
        }

        $user = User::create([
            'username' => $request->username,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user = User::find($user->id);
        $user["nb_favorites"] = $user->favorites()->count();
        $user["nb_projects"] = $user->projects()->count();

        return response()->json([
            'token' => $user->createToken($request->header('User-Agent'))->plainTextToken,
            'user' => $user,
        ], 201);
    }

}
