<?php

namespace App\Services;

use App\Models\User;
use App\Models\EmailVerificationToken;
use App\Notifications\Auth\VerifyEmailNotification;
use App\Http\Resources\SuccessResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function updateUserProfile(int $id, array $data): SuccessResource
    {
        $user = User::find($id);

        if (!$user) {
            throw new ModelNotFoundException('User not found.');
        }

        $email = $data['email'] ?? null;
        $passwordHash = $data['password'] ?? null;
        $username = $data['username'] ?? null;

        if ($username && $user->username !== $username) {
            if (trim($username) === '') {
                throw new ValidationException('Username is required.');
            }

            if (User::where('username', $username)->exists()) {
                throw new ValidationException('Username already in use.');
            }

            $user->username = $username;
        }

        if ($email && $user->email !== $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new ValidationException('Invalid email format.');
            }

            if (User::where('email', $email)->exists()) {
                throw new ValidationException('Email already in use.');
            }

            $user->email = $email;
            $user->email_verified = false;
            $user->save();

            $token = Str::random(60);
            $emailVerificationToken = new EmailVerificationToken([
                'token' => $token,
                'expires_at' => now()->addHours(24),
                'used' => false,
                'user_id' => $user->id,
            ]);
            $emailVerificationToken->save();

            $user->notify(new VerifyEmailNotification());
        }

        if ($passwordHash) {
            $user->password_hash = Hash::make($passwordHash);
        }

        $user->save();

        return new SuccessResource([
            'status' => 200,
            'message' => 'Profile updated successfully.',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'created_at' => $user->created_at->toIso8601String(),
            ],
        ]);
    }

    public function registerNewUser(array $userData): SuccessResource
    {
        $validator = Validator::make($userData, [
            'username' => 'required',
            'password' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if (User::where('username', $userData['username'])->exists()) {
            throw new ValidationException('Username is already in use.', 409);
        }

        if (User::where('email', $userData['email'])->exists()) {
            throw new ValidationException('Email is already in use.', 409);
        }

        $user = User::create([
            'username' => $userData['username'],
            'password_hash' => Hash::make($userData['password']),
            'email' => $userData['email'],
            'email_verified' => false,
        ]);

        $token = Str::random(60);
        $user->emailVerificationTokens()->create([
            'token' => $token,
            'expires_at' => now()->addHours(24),
            'used' => false,
        ]);

        return new SuccessResource($user);
    }
}
