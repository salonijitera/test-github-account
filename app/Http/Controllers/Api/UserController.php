<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest; // Keep the new import for RegisterUserRequest
use App\Http\Requests\User\EditProfileRequest; // Keep the updated import for EditProfileRequest
use App\Http\Resources\SuccessResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

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
        // Check if 'password_hash' key exists to maintain backward compatibility
        $passwordHash = $validatedData['password_hash'] ?? null;
        // Updated parameters to updateUserProfile method, include 'password_hash' if it's provided
        $updateResult = $this->userService->updateUserProfile(
            $validatedData['id'],
            $validatedData['username'] ?? null, // Use null coalescing operator in case 'username' is not provided
            $validatedData['email'],
            $passwordHash
        );

        if ($updateResult) {
            return new SuccessResource('User profile updated successfully.');
        } else {
            return response()->json(['message' => 'Failed to update user profile.', 'success' => false], 400);
        }
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $user = $this->userService->registerNewUser($validatedData);

            return (new SuccessResource([
                'status' => 201,
                'message' => 'User registered successfully.',
                'user' => $user
            ]))->response()->setStatusCode(201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred on the server.'
            ], 500);
        }
    }
}
