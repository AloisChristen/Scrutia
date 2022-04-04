<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param RegistrationRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function register(RegistrationRequest $request)
    {
        $validated = $request->validated();
        if($validated){
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }

        return response()->json(['token' => $user->createToken($request->header('User-Agent'))->plainTextToken]);
    }
}
