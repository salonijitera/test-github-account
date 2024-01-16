<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EditProfileRequest;
use App\Http\Resources\SuccessResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function editProfile(EditProfileRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $updateResult = $this->userService->updateUserProfile($validatedData['id'], $validatedData['email'], $validatedData['password_hash']);

        if ($updateResult) {
            return new SuccessResource('User profile updated successfully.');
        } else {
            return response()->json(['message' => 'Failed to update user profile.', 'success' => false], 400);
        }
    }
}
