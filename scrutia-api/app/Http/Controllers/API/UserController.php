<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get the authenticated user.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        dd(auth()->user());
        return response()->json("Created", 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdateUserRequest $request): JsonResponse
    {
        $user = User::find($id);

        if($user == null) {
            return response()->json(["message" => "Not Found", "errors" => [
                "User does not exist"
            ]], 404);
        }

        $user->username = $request->username;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->save();

        return response()->json("Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        if($user == null) {
            return response()->json(["message" => "Not Found", "errors" => [
                "User does not exist"
            ]], 404);
        }

        if($user->id != auth()->user()->id){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "User not allowed to perform this action"
            ]], 403);
        }

        auth()->user()->tokens()->where('tokenable_id', auth()->id() )->delete();
        $user->likes()->delete();
        $user->delete();

        return response()->json("Deleted");
    }
}
