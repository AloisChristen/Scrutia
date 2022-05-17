<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get the authenticated user.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = User::find(auth()->user()->id);

        if($user == null) {
            return response()->json(["message" => "Not Found", "errors" => [
                "User does not exist"
            ]], 404);
        }

        $user["nb_favorites"] = $user->favorites()->count();
        $user["nb_projects"] = $user->projects()->count();

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = User::find(auth()->user()->id);

        if($user == null) {
            return response()->json(["message" => "Not Found", "errors" => [
                "User does not exist"
            ]], 404);
        }
        if($request->password != null && $request->password_confirmation != null){

            if($request->password != $request->password_confirmation){
                return response()->json(["message" => "Not Allowed", "errors" => [
                    "New password does not match with confirmation"
                ]], 403);
            }

            $user->password = Hash::make($request->password);
        }
        if($request->username != null)
            $user->username = $request->username;
        if($request->firstname != null)
            $user->firstname = $request->firstname;
        if($request->lastname != null)
            $user->lastname = $request->lastname;
        if($request->email != null)
            $user->email = $request->email;

        $user->save();

        return response()->json("Updated");
    }
}
