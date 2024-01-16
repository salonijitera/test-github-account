
<?php

use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function verifyEmail(VerifyEmailRequest $request): JsonResponse
    {
        $response = $this->authService->verifyEmail($request->validated());
        return response()->json($response);
    }
}
