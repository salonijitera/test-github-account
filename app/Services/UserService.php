
<?php

namespace App\Services;

use App\Models\User;
use App\Models\EmailVerificationToken;
use App\Notifications\Auth\VerifyEmailNotification;
use App\Http\Resources\SuccessResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function updateUserProfile(int $id, string $email, string $passwordHash): SuccessResource
    {
        $user = User::find($id);

        if (!$user) {
            throw new ModelNotFoundException('User not found.');
        }

        if ($user->email !== $email) {
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

        $user->password_hash = Hash::make($passwordHash);
        $user->save();

        return new SuccessResource('User profile updated successfully.');
    }
}
