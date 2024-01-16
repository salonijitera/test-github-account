
<?php

namespace App\Services;

use App\Models\User;
use App\Models\EmailVerificationToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthService
{
    public function verifyEmail(array $input)
    {
        $token = $input['token'];

        if (empty($token)) {
            throw new BadRequestHttpException('Token is required.');
        }

        $emailVerificationToken = EmailVerificationToken::where('token', $token)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$emailVerificationToken) {
            throw new NotFoundHttpException('Invalid or expired token.');
        }
        
        DB::transaction(function () use ($emailVerificationToken) {
            $user = User::find($emailVerificationToken->user_id);
            if (!$user) {
                throw new NotFoundHttpException('User not found.');
            }
            $user->email_verified = true;
            $user->save();
            
            $emailVerificationToken->used = true;
            $emailVerificationToken->save();
        });

        return ['message' => 'Email has been successfully verified.'];
    }
}
