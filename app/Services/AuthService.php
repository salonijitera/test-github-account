<?php

namespace App\Services;

use App\Models\User;
use App\Models\EmailVerificationToken;
use App\Notifications\Auth\VerifyEmailNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthService
{
    public function registerNewUser(string $username, string $password, string $email): array
    {
        if (empty($username) || empty($password) || empty($email)) {
            throw new \InvalidArgumentException('Username, password, and email cannot be empty.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format.');
        }

        if (User::where('email', $email)->exists()) {
            throw new \InvalidArgumentException('Email already in use.');
        }

        $passwordHash = Hash::make($password);

        $user = User::create([
            'username' => $username,
            'password_hash' => $passwordHash,
            'email' => $email,
            'email_verified' => false,
            'created_at' => now(),
        ]);

        $token = Str::random(60);
        $expiresAt = Carbon::now()->addHours(24);

        EmailVerificationToken::create([
            'token' => $token,
            'expires_at' => $expiresAt,
            'used' => false,
            'user_id' => $user->id,
        ]);

        $user->notify(new VerifyEmailNotification($token));

        return [
            'message' => 'Registration successful. Please check your email to verify your account.'
        ];
    }
}
